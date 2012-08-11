<?php
class ComLanguagesDatabaseRowItem extends KDatabaseRowTable
{
    /**
     * Status = unknown
     */
    const STATUS_UNKNOWN = 0;

    /**
     * Status = completed
     */
    const STATUS_COMPLETED = 1;

    /**
     * Status = missing
     */
    const STATUS_MISSING = 2;

    /**
     * Status = outdated
     */
    const STATUS_OUTDATED = 3;

    /**
     * Status = pending
     */
    const STATUS_PENDING = 4;
}