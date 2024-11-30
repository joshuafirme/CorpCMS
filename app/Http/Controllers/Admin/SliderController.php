<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Utils;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $data = Slider::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.sliders.list', compact('data'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
       // $data['user_id'] = $request->user()->id;

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/sliders/');
        }

        Slider::create($data);

        return redirect()->back()->with('success', 'Slider was added.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);
       // $data['user_id'] = $request->user()->id;

        if ($request->image) {
            $data['image'] = Utils::uploadFile($request->image, 'uploads/img/sliders/');
        }

        Slider::where('id', $id)->update($data);
        return redirect()->back()->with('success', 'Slider was updated.');
    }


    public function destroy($id)
    {

        $query = Slider::where('id', $id);
        if ($query->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Data was deleted.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Deletion failed'
        ]);
    }
}
