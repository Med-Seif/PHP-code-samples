<?php
/**
 * User Seif <ben.s@mipih.fr>
 * Date time: 16/10/2018 10:19
 */

namespace Gta\TracabiliteBundle\Log\Formatter;

use Gta\TracabiliteBundle\Resources\StringConstants;

/**
 * Class DbApiJournalLogFormatter
 *
 * @package Gta\TracabiliteBundle\Log\Formatter
 * @author  Seif <ben.s@mipih.fr>
 */
class ApiJournalLogFormatter extends AbstractDbTableLogFormatter
{
    /**
     * @var string Nom de la table ou on va écrire
     */
    protected $dbTableName = 'API_JOURNAL';

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function format(array $record)
    {
        if (!$record) {
            return [];
        }
        $context = $record['context'];
        $extra = $record ['extra'];
        $requestQueryParams = (count($context[StringConstants::REQUEST_QUERY_PARAMS]) > 0) ? json_encode(
            $context[StringConstants::REQUEST_QUERY_PARAMS]
        ) : null;
        $params = [
            StringConstants::SESSION_ID,
            StringConstants::REQUEST_DATE,
            StringConstants::USERNAME,
            StringConstants::REQUEST_QUERY_PARAMS,
            StringConstants::CODHOP,
            StringConstants::ROUTE_NAME,
            StringConstants::REQUEST_ACTION_NAME,
            StringConstants::APPCOD,
            StringConstants::APPVERSION,
        ];
        $placeholders = [];
        foreach ($params as $param) {
            if (StringConstants::REQUEST_DATE == $param) {
                $placeholders [$param] = 'to_date(:'.StringConstants::REQUEST_DATE.',\'dd/mm/yyyy HH24:MI:SS\')';
            } else {
                $placeholders [$param] = ':'.$param;
            }
        }
        $parameters = [
            StringConstants::SESSION_ID           => $extra[StringConstants::SESSION_ID],
            StringConstants::REQUEST_DATE         => $context[StringConstants::REQUEST_DATE],
            StringConstants::USERNAME             => $extra[StringConstants::USERNAME],
            StringConstants::REQUEST_QUERY_PARAMS => $requestQueryParams,
            StringConstants::CODHOP               => $extra[StringConstants::CODHOP],
            StringConstants::ROUTE_NAME           => $context[StringConstants::ROUTE_NAME],
            StringConstants::REQUEST_ACTION_NAME  => $context[StringConstants::REQUEST_ACTION_NAME],
            StringConstants::APPCOD               => $extra[StringConstants::APPCOD],
            StringConstants::APPVERSION           => $extra[StringConstants::APPVERSION],
        ];

        return [
            StringConstants::PARAMETERS_KEY    => $parameters,
            StringConstants::PLACE_HOLDERS_KEY => $placeholders,
        ];
    }

    /**
     * {@inheritdoc}
     * @author Seif <ben.s@mipih.fr>
     */
    public function formatBatch(array $records)
    {
        return $records;
    }
}