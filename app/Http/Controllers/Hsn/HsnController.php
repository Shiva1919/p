<?php

namespace App\Http\Controllers\Hsn;

use App\Http\Controllers\Controller;
use App\Models\Hsn\Hsncode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HsnController extends Controller
{

    public function Index()
    {
        return view('hsn.hsn');
    }
    function importData(Request $request){
        set_time_limit(0);
        $this->validate($request, [
            'file' => 'required|file|mimes:xls,xlsx'
        ]);
        $the_file = $request->file('file');
        $spreadsheet = IOFactory::load($the_file->getRealPath());
        $data =    $spreadsheet->getSheetByName('HSN')->toArray();
        $data1 = $spreadsheet->getSheetByName('SAC')->toArray();
//hsn
        foreach($data as $row)
        {
            if ((int)$row['0']){
            $hsn =DB::connection('mysql_2')->table('HsnCodesTable')->where("hsncode",$row['0'])->get();
            if(count($hsn)== 0)
            {
                $hsncode = $row['0'];
                $decription = $row['1'];
                $Hsncode = new Hsncode;
                $Hsncode->hsncode = $row['0'];
                $Hsncode->hsndesc = $row['1'];
                $Hsncode->hsntype = 'Goods';
                $Hsncode->hsncodestring = $row['0'];
                $Hsncode->save();
                $msg = true;
            }
            else{
                $msg_allready = true;
            }
            }
        }
//SAC
        foreach($data1 as $row1)
        {
            if ((int)$row1['0']){
                $hsn1 =DB::connection('mysql_2')->table('HsnCodesTable')->where("hsncode",$row1['0'])->get();

            if(count($hsn1)== 0)
            {
                $Hsncode1 = new Hsncode;
                $Hsncode1->hsncode = $row1['0'];
                $Hsncode1->hsndesc = $row1['1'];
                $Hsncode1->hsntype = 'Services';
                $Hsncode1->hsncodestring = $row1['0'];
                $Hsncode1->save();
                $msg = true;
            }
            else{
                $msg_allready = true;
            }
        }
        }

        if(isset($msg))
        {
            return redirect('Acme_hsn')->with(['success'=>'Successfully Imported']);

        }
        else if(isset($msg_allready))
        {
            return redirect('Acme_hsn')->with(['allready'=>'Allready Imported']);
        }
        else{
            return redirect('Acme_hsn')->with(['error'=>'Not Imported']);
             }

    }

}
