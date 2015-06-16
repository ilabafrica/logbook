<?php namespace App\Http\Requests;
use Input;

class UserListImport extends \Maatwebsite\Excel\Files\ExcelFile {
    /* optional CSV settings, like $delimiter, $enclosure and $lineEnding */
    protected $delimiter  = ',';
    protected $enclosure  = '"';
    protected $lineEnding = '\r\n';

    public function getFile()
    {
        // Import a user provided file
        $file = Input::file('excel');
        $ext = $file->getClientOriginalExtension();
        $excel = uniqid().'.'.$ext;
        $filename = $file->move('uploads/', $excel);

        // Return it's location
        return $excel;
    }
}