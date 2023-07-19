<?php

namespace src\Helper;

use Illuminate\Support\Facades\Schema;
use Symfony\Component\VarDumper\VarDumper;

class AppendModelFillables
{

    /**
     * @var AppendModelFillables
     * @author Shaarif<shaarifsabah5299@gmail.com>
     */

    /**
     * @param $tableName string
     * @param $model string
     * @return void
     */
    public static function AppendFieldsToModel(string $tableName, string $model)
    {
        # Get fileName
        $fileName      = app_path('Models/') . $model . '.php';
        # get migrations Columns
        $columns       = Schema::getColumnListing($tableName);
        # Read Model
        $getContentOfModel = file_get_contents($fileName);
        # check for already fillables existence
        $alreadyExist = strpos( $getContentOfModel,'protected $fillable');
        # if false then update the Model
        if ($alreadyExist === false) {
            $modelUpperBody  = substr($getContentOfModel, '0', 180);
            $modelLowerBody  = substr($getContentOfModel, 180);
            $fillAbles = [];
            # adding ColumnNames in array to append
            foreach ($columns as $column) {
                if ($column == 'created_at' || $column == 'updated_at'){}
                else
                    $fillAbles[] = "\t" ."\n". "\t"."'" .$column ."'"  ;
            }
            $fillableToAppend = 'protected $fillable = [' . implode(',', $fillAbles) . "\n" . "\t" .'];';
            $updatedModel        = $modelUpperBody ."\t". "\n". "\t" . $fillableToAppend . "\n" . $modelLowerBody;
            file_put_contents($fileName, $updatedModel);
            VarDumper::dump("Model fillAbles updated");
        }
    }
}
