<?php

namespace EditRobotTxt\Model\Base;

use \Exception;
use \PDO;
use EditRobotTxt\Model\Robots as ChildRobots;
use EditRobotTxt\Model\RobotsQuery as ChildRobotsQuery;
use EditRobotTxt\Model\Map\RobotsTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'robots' table.
 *
 *
 *
 * @method     ChildRobotsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildRobotsQuery orderByDomainName($order = Criteria::ASC) Order by the domain_name column
 * @method     ChildRobotsQuery orderByRobotsContent($order = Criteria::ASC) Order by the robots_content column
 *
 * @method     ChildRobotsQuery groupById() Group by the id column
 * @method     ChildRobotsQuery groupByDomainName() Group by the domain_name column
 * @method     ChildRobotsQuery groupByRobotsContent() Group by the robots_content column
 *
 * @method     ChildRobotsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRobotsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRobotsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRobots findOne(ConnectionInterface $con = null) Return the first ChildRobots matching the query
 * @method     ChildRobots findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRobots matching the query, or a new ChildRobots object populated from the query conditions when no match is found
 *
 * @method     ChildRobots findOneById(int $id) Return the first ChildRobots filtered by the id column
 * @method     ChildRobots findOneByDomainName(string $domain_name) Return the first ChildRobots filtered by the domain_name column
 * @method     ChildRobots findOneByRobotsContent(string $robots_content) Return the first ChildRobots filtered by the robots_content column
 *
 * @method     array findById(int $id) Return ChildRobots objects filtered by the id column
 * @method     array findByDomainName(string $domain_name) Return ChildRobots objects filtered by the domain_name column
 * @method     array findByRobotsContent(string $robots_content) Return ChildRobots objects filtered by the robots_content column
 *
 */
abstract class RobotsQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \EditRobotTxt\Model\Base\RobotsQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\EditRobotTxt\\Model\\Robots', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRobotsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRobotsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \EditRobotTxt\Model\RobotsQuery) {
            return $criteria;
        }
        $query = new \EditRobotTxt\Model\RobotsQuery();
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildRobots|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RobotsTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RobotsTableMap::DATABASE_NAME);
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
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildRobots A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, DOMAIN_NAME, ROBOTS_CONTENT FROM robots WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildRobots();
            $obj->hydrate($row);
            RobotsTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildRobots|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RobotsTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RobotsTableMap::ID, $keys, Criteria::IN);
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
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RobotsTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RobotsTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RobotsTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the domain_name column
     *
     * Example usage:
     * <code>
     * $query->filterByDomainName('fooValue');   // WHERE domain_name = 'fooValue'
     * $query->filterByDomainName('%fooValue%'); // WHERE domain_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $domainName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function filterByDomainName($domainName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($domainName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $domainName)) {
                $domainName = str_replace('*', '%', $domainName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RobotsTableMap::DOMAIN_NAME, $domainName, $comparison);
    }

    /**
     * Filter the query on the robots_content column
     *
     * Example usage:
     * <code>
     * $query->filterByRobotsContent('fooValue');   // WHERE robots_content = 'fooValue'
     * $query->filterByRobotsContent('%fooValue%'); // WHERE robots_content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $robotsContent The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function filterByRobotsContent($robotsContent = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($robotsContent)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $robotsContent)) {
                $robotsContent = str_replace('*', '%', $robotsContent);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(RobotsTableMap::ROBOTS_CONTENT, $robotsContent, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRobots $robots Object to remove from the list of results
     *
     * @return ChildRobotsQuery The current query, for fluid interface
     */
    public function prune($robots = null)
    {
        if ($robots) {
            $this->addUsingAlias(RobotsTableMap::ID, $robots->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the robots table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RobotsTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RobotsTableMap::clearInstancePool();
            RobotsTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildRobots or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildRobots object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RobotsTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RobotsTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        RobotsTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RobotsTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // RobotsQuery
