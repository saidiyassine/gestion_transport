<?php

namespace App\Http\Controllers;
use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
        public function lister(Request $request)
        {
            $name = $request->input('name');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Remplacer Employe par Transport
            $query = Transport::where('is_deleted', 0);

            if ($name) {
                $query->where('name', 'LIKE', "%$name%");
            }

            if ($latitude) {
                $query->where('center_lat', 'LIKE', "%$latitude%");
            }

            if ($longitude) {
                $query->where('center_lng', 'LIKE', "%$longitude%");
            }

            $total_transports = $query->count(); // Modifier pour le total des transports

            $transports = $query->orderBy('id', 'asc')->paginate(10); // Modifier pour les transports

            if ($total_transports === 0) {
                session()->flash('attention', 'Aucun transport avec ces critères');
            }

            return view("admin.transports.transports", compact("transports", "total_transports")); // Modifier la vue
        }

        public function addForm()
        {
            return view("admin.transports.addTransport"); // Modifier la vue d'ajout
        }

        public function addT(Request $request)
        {
            $transport = new Transport(); // Modifier pour utiliser Transport
            $transport->name = $request->name;
            $transport->center_lat = $request->center_lat;
            $transport->center_lng = $request->center_lng;
            $transport->capacity = $request->capacity; // Ajoute ce champ si nécessaire
            $transport->radius = $request->radius;
            $transport->is_deleted = 0;
            $transport->save();

            return redirect("admin/transports/lister")->with('success', 'Transport ajouté avec succès.'); // Modifier la redirection
        }

        public function showLocation($id)
        {
            $transport = Transport::findOrFail($id);
            return view('admin.transports.location', compact('transport'));
        }

        public function editer($id)
        {
            $transport = Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }
            return view('admin.transports.editer', compact('transport'));
        }

        public function modifier(Request $request, $id)
        {
            $transport = Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }

            $transport->name = $request->input('name');
            $transport->center_lat = $request->input('center_lat');
            $transport->center_lng = $request->input('center_lng');
            $transport->capacity = $request->input('capacity');
            $transport->radius = $request->input('radius');

            $transport->save();
            return redirect('admin/transports/lister')->with('success', 'Transport mis à jour avec succès');
        }

        public function supprimer($id){
            $transport=Transport::find($id);

            if (!$transport) {
                return redirect()->back()->with('error', 'Transport non trouvé');
            }
            $transport->is_deleted="1";
            $transport->save();
            return redirect('admin/transports/lister')->with('success', 'Transport supprimé avec succès');
        }

        public function showAllZones()
        {
            $transports = Transport::all(); 
            return view('admin.transports.allZones', compact('transports'));
        }

}