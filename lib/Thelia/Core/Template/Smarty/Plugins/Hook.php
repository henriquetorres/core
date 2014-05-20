<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Core\Template\Smarty\Plugins;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Template\Smarty\SmartyParser;
use Thelia\Core\Template\Smarty\SmartyPluginDescriptor;
use Thelia\Core\Template\Smarty\AbstractSmartyPlugin;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Thelia\Core\Translation\Translator;
use Thelia\Log\Tlog;

/**
 * Plugin for smarty defining blocks and functions for using Hooks.
 *
 * Class Hook
 * @package Thelia\Core\Template\Smarty\Plugins
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class Hook extends AbstractSmartyPlugin
{

    private $dispatcher;

    /** @var Translator */
    protected $translator;

    /** @var array  */
    protected $hookResults = array();

    /** @var bool debug */
    protected $debug = false;

    public function __construct($debug, ContainerAwareEventDispatcher $dispatcher)
    {
        $this->debug = $debug;
        $this->dispatcher = $dispatcher;
        $this->translator = $dispatcher->getContainer()->get("thelia.translator");
        $this->hookResults = array();
    }

    /**
     * Generates the content of the hook
     *
     * {hook name="hook_code" var1="value1" var2="value2" ... }
     *
     * This function create an event, feed it with the custom variables passed to the function (var1, var2, ...) and
     * dispatch it to the hooks that respond to it.
     *
     * The name of the event is `hook.{context}.{hook_code}` where :
     *      * context : the id of the context of the smarty render : 1: frontoffice, 2: backoffice, 3: email, 4: pdf
     *      * hook_code : the code of the hook
     *
     * The event collects all the fragments of text rendered in each modules functions that listen to this event.
     * Finally, this fragments are concatenated and injected in the template
     *
     * @param  array        $params the params passed in the smarty function
     * @param  SmartyParser $smarty the smarty parser
     * @return string       the contents generated by modules
     */
    public function processHookFunction($params, &$smarty)
    {
        $hookName = $this->getParam($params, 'name');
        $module = intval($this->getParam($params, 'module', 0));
        $type = $smarty->getTemplateDefinition()->getType();

        Tlog::getInstance()->addDebug("_HOOK_ process hook : " . $hookName);

        $event = new HookRenderEvent($hookName, $params, $module);
        $event->setArguments($this->getArgumentsFromParams($params));

        // todo implement a before hook
        // $event = $this->getDispatcher()->dispatch('hook.before.' . $hookName, $event);
        $eventName = sprintf('hook.%s.%s', $type, $hookName);
        // thi is a hook specific to a module
        if (0 !== $module){
            $eventName .= '.' . $module;
        }
        Tlog::getInstance()->addDebug("_HOOK_ dispatch event : " . $eventName);
        $this->getDispatcher()->dispatch($eventName, $event);
        // todo implement a after hook for post treatment on event
        // $event = $this->getDispatcher()->dispatch('hook.after.' . $hookName, $event);

        $content = trim($event->dump());

        if ($this->debug && $smarty->getRequest()->get('SHOW_HOOK')) {
            $content = sprintf('<div style="background-color: #C82D26; color: #fff; border-color: #000000; border: solid;">%s</div>%s', $hookName, $content);
        }

        $this->hookResults[$hookName] = $content;

        return $content;
    }

    /**
     * Generates the content of the hookBlock block
     *
     * @param  array        $params
     * @param  string       $content
     * @param  SmartyParser $smarty
     * @param  bool         $repeat
     * @return string       no text is returned.
     */
    public function processHookBlock($params, $content, $smarty, &$repeat)
    {

        $hookName = $this->getParam($params, 'name');

        if (! $repeat) {
            if ($this->debug && $smarty->getRequest()->get('SHOW_HOOK')) {
                $content = sprintf('<div style="background-color: #C82D26; color: #fff; border-color: #000000; border: solid;">%s</div>', $hookName)
                    . $content;
            }
            return $content;
        }

        $type = $smarty->getTemplateDefinition()->getType();

        Tlog::getInstance()->addDebug("_HOOK_ process hook block : " . $hookName);

        $event = new HookRenderBlockEvent($hookName, $params);

        // todo implement a before hook
        // $event = $this->getDispatcher()->dispatch('hook.before.' . $hookName, $event);
        $this->getDispatcher()->dispatch('hook.' . $type . '.' . $hookName, $event);
        // todo implement a after hook for post treatment on event
        // $event = $this->getDispatcher()->dispatch('hook.after.' . $hookName, $event);

        $isEmpty = true;
        foreach ($event->keys() as $key) {
            // Tlog::getInstance()->addDebug("_HOOK_ block assign : " . $key);
            $content = $event->get($key);
            if (0 !== count($content)) {
                $isEmpty = false;
            }
            $smarty->assign($key, $content);
        }
        // it's a bit dirty but we just add a content to enable the ifHook/elseHook support
        $this->hookResults[$hookName] = $isEmpty ? "" : "not empty";

    }

    /**
     * Process {elseHook rel="hookname"} ... {/elseHook} block
     *
     * @param  array                     $params   hook parameters
     * @param  string                    $content  hook text content
     * @param  \Smarty_Internal_Template $template the Smarty object
     * @param  boolean                   $repeat   repeat indicator (see Smarty doc.)
     * @return string                    the hook output
     */
    public function elseHook($params, $content, /** @noinspection PhpUnusedParameterInspection */ $template, &$repeat)
    {
        // When encountering close tag, check if hook has results.
        if ($repeat === false) {
            return $this->checkEmptyHook($params) ? $content : '';
        }

        return '';
    }

    /**
     * Process {ifHook rel="hookname"} ... {/ifHook} block
     *
     * @param  array                     $params   hook parameters
     * @param  string                    $content  hook text content
     * @param  \Smarty_Internal_Template $template the Smarty object
     * @param  boolean                   $repeat   repeat indicator (see Smarty doc.)
     * @return string                    the hook output
     */
    public function ifHook($params, $content, /** @noinspection PhpUnusedParameterInspection */ $template, &$repeat)
    {
        // When encountering close tag, check if hook has results.
        if ($repeat === false) {
            return $this->checkEmptyHook($params) ? '' : $content;
        }

        return '';
    }


    /**
     * Check if a hook has returned results. The hook should have been executed before, or an
     * InvalidArgumentException is thrown
     *
     * @param array $params
     *
     * @return boolean                   true if the hook is empty
     * @throws \InvalidArgumentException
     */
    protected function checkEmptyHook($params)
    {
        $hookName = $this->getParam($params, 'rel');

        if (null == $hookName)
            throw new \InvalidArgumentException(
                $this->translator->trans("Missing 'rel' parameter in ifHook/elseHook arguments")
            );

        if (! isset($this->hookResults[$hookName]))
            throw new \InvalidArgumentException(
                $this->translator->trans("Related hook name '%name' is not defined.", ['%name' => $hookName])
            );

        return ('' === $this->hookResults[$hookName]);
    }


    /**
     * Clean the params of the params passed to the hook function or block to feed the arguments of the event
     * with relevant arguments.
     *
     * @param        $params
     * @return array
     */
    protected function getArgumentsFromParams($params)
    {
        $args = array();
        $excludes = array("name", "before", "separator", "after");

        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (! in_array($key, $excludes)) {
                    $args[$key] = $value;
                }
            }
        }

        return $args;
    }

    /**
     * Define the various smarty plugins handled by this class
     *
     * @return an array of smarty plugin descriptors
     */
    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor('function', 'hook', $this, 'processHookFunction'),
            new SmartyPluginDescriptor('block', 'hookBlock', $this, 'processHookBlock'),
            new SmartyPluginDescriptor('block', 'elseHook', $this, 'elseHook'),
            new SmartyPluginDescriptor('block', 'ifHook', $this, 'ifHook'),
        );
    }

    /**
     * Return the event dispatcher,
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

}
