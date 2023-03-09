<?php namespace AcornAssociated\Calendar\Updates;

use Winter\Storm\Database\Updates\Migration;
use DB;

class AcornAssociatedMigration extends Migration
{
    // TODO: Move this class in to a common tools module and create dependencies

    public function dropCascade($table)
    {
        DB::unprepared("drop table if exists $table cascade");
    }

    public function createFunction(string $name, array $parameters, string $returnType, string $body, ?string $language = 'plpgsql')
    {
        $BODY = '$BODY$';
        $parametersString = implode(',', $parameters);
        DB::unprepared(<<<SQL
            create or replace function $name($parametersString) returns $returnType
            as $BODY
            begin
                $body
            end;
            $BODY language $language;
SQL
        );
    }

    public function interval(string $table, string $column, ?bool $nullable = FALSE)
    {
        $null = ($nullable ? '' : 'NOT NULL');
        DB::unprepared("alter table $table add column $column interval $null;");
    }
}
