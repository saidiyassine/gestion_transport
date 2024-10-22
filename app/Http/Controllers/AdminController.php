<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;

class AdminController extends Controller
{
    public function dashboard(){
        return view("admin.dashboard");
    }

    public function lister(Request $request)
    {
        $name = $request->input('name');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $query = Employe::where('is_deleted', 0);

        if ($name) {
            $query->where('name', 'LIKE', "%$name%");
        }

        if ($latitude) {
            $query->where('latitude', 'LIKE', "%$latitude%");
        }
        if ($longitude) {
            $query->where('longitude', 'LIKE', "%$longitude%");
        }

        $total_employees = $query->count();

        $employees = $query->orderBy('id', 'desc')->paginate(10);

        if ($total_employees === 0) {
            session()->flash('attention', 'Aucun employe avec ces critères');
        }

        return view("admin.employes.employes", compact("employees", "total_employees"));
    }

    public function addForm(){
        return view("admin.employes.addE");
    }

    public function addE(Request $request)
    {
        $employe = new Employe();
        $employe->name = $request->name;
        $employe->latitude = $request->latitude;
        $employe->longitude = $request->longitude;
        $employe->is_deleted = 0;
        $employe->save();

        return redirect("admin/employes/lister")->with('success', 'Employé ajouté avec succès.');
    }
}
