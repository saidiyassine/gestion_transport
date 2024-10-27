<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\Transport;

class EmployeController extends Controller
{
    public function dashboard() {
        $employes = Employe::count();
        $transports = Transport::count();
        return view("admin.dashboard", compact("employes", "transports"));
    }

    public function lister(Request $request)
    {
        $name = $request->input('name');
        $mat = $request->input('mat');

        $query = Employe::where('is_deleted', 0);

        if ($name) {
            $query->where('name', 'LIKE', "%$name%");
        }

        if ($mat) {
            $query->where('Mat', '=', "$mat");
        }

        $total_employees = $query->count();

        $employees = $query->orderBy('Mat', 'asc')->paginate(10);

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
        $employe->Mat = $request->mat;
        $employe->name = $request->name;
        $employe->latitude = $request->latitude;
        $employe->longitude = $request->longitude;
        $employe->is_deleted = 0;
        $employe->save();

        return redirect("admin/employes/lister")->with('success', 'Employé ajouté avec succès.');
    }

    public function editer($id){
        $employe=Employe::find($id);
        return view('admin.employes.editer', compact('employe'));
    }

    public function update(Request $request, $id)
        {
            $employe = Employe::find($id);

            if (!$employe) {
                return redirect()->back()->with('error', 'Employé(e) non trouvé');
            }

            $employe->Mat = $request->input('mat');
            $employe->name = $request->input('name');
            $employe->latitude = $request->input('latitude');
            $employe->longitude = $request->input('longitude');

            $employe->save();
            return redirect('admin/employes/lister')->with('success', "L'employé a été mis à jour avec succès");
        }

        public function delete($id){
            $employe=Employe::find($id);

            if (!$employe) {
                return redirect()->back()->with('error', 'Employé non trouvé');
            }
            $employe->is_deleted="1";
            $employe->save();
            return redirect('admin/employes/lister')->with('success', "L'employé a été supprimé avec succès");
        }

        public function showLocation($id)
        {
            $employe = Employe::findOrFail($id);
            return view('admin.employes.location', compact('employe'));
        }

}