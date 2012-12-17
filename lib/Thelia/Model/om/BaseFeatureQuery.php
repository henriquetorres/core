<?php

namespace Thelia\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Thelia\Model\Feature;
use Thelia\Model\FeatureAv;
use Thelia\Model\FeatureCategory;
use Thelia\Model\FeatureDesc;
use Thelia\Model\FeaturePeer;
use Thelia\Model\FeatureProd;
use Thelia\Model\FeatureQuery;

/**
 * Base class that represents a query for the 'feature' table.
 *
 *
 *
 * @method FeatureQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FeatureQuery orderByVisible($order = Criteria::ASC) Order by the visible column
 * @method FeatureQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method FeatureQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FeatureQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FeatureQuery groupById() Group by the id column
 * @method FeatureQuery groupByVisible() Group by the visible column
 * @method FeatureQuery groupByPosition() Group by the position column
 * @method FeatureQuery groupByCreatedAt() Group by the created_at column
 * @method FeatureQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FeatureQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FeatureQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FeatureQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FeatureQuery leftJoinFeatureAv($relationAlias = null) Adds a LEFT JOIN clause to the query using the FeatureAv relation
 * @method FeatureQuery rightJoinFeatureAv($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FeatureAv relation
 * @method FeatureQuery innerJoinFeatureAv($relationAlias = null) Adds a INNER JOIN clause to the query using the FeatureAv relation
 *
 * @method FeatureQuery leftJoinFeatureCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the FeatureCategory relation
 * @method FeatureQuery rightJoinFeatureCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FeatureCategory relation
 * @method FeatureQuery innerJoinFeatureCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the FeatureCategory relation
 *
 * @method FeatureQuery leftJoinFeatureDesc($relationAlias = null) Adds a LEFT JOIN clause to the query using the FeatureDesc relation
 * @method FeatureQuery rightJoinFeatureDesc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FeatureDesc relation
 * @method FeatureQuery innerJoinFeatureDesc($relationAlias = null) Adds a INNER JOIN clause to the query using the FeatureDesc relation
 *
 * @method FeatureQuery leftJoinFeatureProd($relationAlias = null) Adds a LEFT JOIN clause to the query using the FeatureProd relation
 * @method FeatureQuery rightJoinFeatureProd($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FeatureProd relation
 * @method FeatureQuery innerJoinFeatureProd($relationAlias = null) Adds a INNER JOIN clause to the query using the FeatureProd relation
 *
 * @method Feature findOne(PropelPDO $con = null) Return the first Feature matching the query
 * @method Feature findOneOrCreate(PropelPDO $con = null) Return the first Feature matching the query, or a new Feature object populated from the query conditions when no match is found
 *
 * @method Feature findOneById(int $id) Return the first Feature filtered by the id column
 * @method Feature findOneByVisible(int $visible) Return the first Feature filtered by the visible column
 * @method Feature findOneByPosition(int $position) Return the first Feature filtered by the position column
 * @method Feature findOneByCreatedAt(string $created_at) Return the first Feature filtered by the created_at column
 * @method Feature findOneByUpdatedAt(string $updated_at) Return the first Feature filtered by the updated_at column
 *
 * @method array findById(int $id) Return Feature objects filtered by the id column
 * @method array findByVisible(int $visible) Return Feature objects filtered by the visible column
 * @method array findByPosition(int $position) Return Feature objects filtered by the position column
 * @method array findByCreatedAt(string $created_at) Return Feature objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Feature objects filtered by the updated_at column
 *
 * @package    propel.generator.Thelia.Model.om
 */
abstract class BaseFeatureQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFeatureQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'mydb', $modelName = 'Thelia\\Model\\Feature', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FeatureQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     FeatureQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FeatureQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FeatureQuery) {
            return $criteria;
        }
        $query = new FeatureQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Feature|Feature[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FeaturePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FeaturePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Feature A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `VISIBLE`, `POSITION`, `CREATED_AT`, `UPDATED_AT` FROM `feature` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Feature();
            $obj->hydrate($row);
            FeaturePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Feature|Feature[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Feature[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FeaturePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FeaturePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @see       filterByFeatureAv()
     *
     * @see       filterByFeatureCategory()
     *
     * @see       filterByFeatureDesc()
     *
     * @see       filterByFeatureProd()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(FeaturePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the visible column
     *
     * Example usage:
     * <code>
     * $query->filterByVisible(1234); // WHERE visible = 1234
     * $query->filterByVisible(array(12, 34)); // WHERE visible IN (12, 34)
     * $query->filterByVisible(array('min' => 12)); // WHERE visible > 12
     * </code>
     *
     * @param     mixed $visible The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByVisible($visible = null, $comparison = null)
    {
        if (is_array($visible)) {
            $useMinMax = false;
            if (isset($visible['min'])) {
                $this->addUsingAlias(FeaturePeer::VISIBLE, $visible['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($visible['max'])) {
                $this->addUsingAlias(FeaturePeer::VISIBLE, $visible['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeaturePeer::VISIBLE, $visible, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position > 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(FeaturePeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(FeaturePeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeaturePeer::POSITION, $position, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FeaturePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FeaturePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeaturePeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FeaturePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FeaturePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FeaturePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related FeatureAv object
     *
     * @param   FeatureAv|PropelObjectCollection $featureAv The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   FeatureQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByFeatureAv($featureAv, $comparison = null)
    {
        if ($featureAv instanceof FeatureAv) {
            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureAv->getFeatureId(), $comparison);
        } elseif ($featureAv instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureAv->toKeyValue('PrimaryKey', 'FeatureId'), $comparison);
        } else {
            throw new PropelException('filterByFeatureAv() only accepts arguments of type FeatureAv or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FeatureAv relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function joinFeatureAv($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FeatureAv');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FeatureAv');
        }

        return $this;
    }

    /**
     * Use the FeatureAv relation FeatureAv object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\FeatureAvQuery A secondary query class using the current class as primary query
     */
    public function useFeatureAvQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFeatureAv($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FeatureAv', '\Thelia\Model\FeatureAvQuery');
    }

    /**
     * Filter the query by a related FeatureCategory object
     *
     * @param   FeatureCategory|PropelObjectCollection $featureCategory The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   FeatureQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByFeatureCategory($featureCategory, $comparison = null)
    {
        if ($featureCategory instanceof FeatureCategory) {
            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureCategory->getFeatureId(), $comparison);
        } elseif ($featureCategory instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureCategory->toKeyValue('PrimaryKey', 'FeatureId'), $comparison);
        } else {
            throw new PropelException('filterByFeatureCategory() only accepts arguments of type FeatureCategory or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FeatureCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function joinFeatureCategory($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FeatureCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FeatureCategory');
        }

        return $this;
    }

    /**
     * Use the FeatureCategory relation FeatureCategory object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\FeatureCategoryQuery A secondary query class using the current class as primary query
     */
    public function useFeatureCategoryQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFeatureCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FeatureCategory', '\Thelia\Model\FeatureCategoryQuery');
    }

    /**
     * Filter the query by a related FeatureDesc object
     *
     * @param   FeatureDesc|PropelObjectCollection $featureDesc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   FeatureQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByFeatureDesc($featureDesc, $comparison = null)
    {
        if ($featureDesc instanceof FeatureDesc) {
            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureDesc->getFeatureId(), $comparison);
        } elseif ($featureDesc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureDesc->toKeyValue('PrimaryKey', 'FeatureId'), $comparison);
        } else {
            throw new PropelException('filterByFeatureDesc() only accepts arguments of type FeatureDesc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FeatureDesc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function joinFeatureDesc($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FeatureDesc');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FeatureDesc');
        }

        return $this;
    }

    /**
     * Use the FeatureDesc relation FeatureDesc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\FeatureDescQuery A secondary query class using the current class as primary query
     */
    public function useFeatureDescQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFeatureDesc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FeatureDesc', '\Thelia\Model\FeatureDescQuery');
    }

    /**
     * Filter the query by a related FeatureProd object
     *
     * @param   FeatureProd|PropelObjectCollection $featureProd The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   FeatureQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByFeatureProd($featureProd, $comparison = null)
    {
        if ($featureProd instanceof FeatureProd) {
            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureProd->getFeatureId(), $comparison);
        } elseif ($featureProd instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FeaturePeer::ID, $featureProd->toKeyValue('PrimaryKey', 'FeatureId'), $comparison);
        } else {
            throw new PropelException('filterByFeatureProd() only accepts arguments of type FeatureProd or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FeatureProd relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function joinFeatureProd($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FeatureProd');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FeatureProd');
        }

        return $this;
    }

    /**
     * Use the FeatureProd relation FeatureProd object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Thelia\Model\FeatureProdQuery A secondary query class using the current class as primary query
     */
    public function useFeatureProdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFeatureProd($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FeatureProd', '\Thelia\Model\FeatureProdQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Feature $feature Object to remove from the list of results
     *
     * @return FeatureQuery The current query, for fluid interface
     */
    public function prune($feature = null)
    {
        if ($feature) {
            $this->addUsingAlias(FeaturePeer::ID, $feature->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
