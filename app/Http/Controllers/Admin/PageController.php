<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $q = Page::query();
    
        $d['pagination']='10';
        if($request->search){
          
            $q->where('title', 'like', "%$request->search%")->paginate(10);
        }
        $d['page']=$q->orderBy('title', 'ASC')->paginate($d['pagination']);

        return view('admin.page.index', $d);
    }

    public function create()
    {
        return view('admin.page.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required | string',
        ]);

        $request['slug'] = Str::slug($request->title);
        $request['new_slug'] = $request['slug'];

        $count=1;
        while(Page::where('slug', '=', $request['new_slug'])->exists())
            {
                $request['new_slug'] = $request['slug'].'-'.$count;
                $count++;
            }

        $pages = Page::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'slug' => $request['new_slug'],
                'content' => $request->content,
            ]
        );

        $result = $pages->update();

        if($result)
        {
            if($request->id)
            {
                session()->flash('success','Page Updated successfully');
                return redirect()->route('admin.page.index');
            }
            else
            {
                session()->flash('success','Page Created successfully');
                return redirect()->route('admin.page.index');
            }
        }
        else
        {
            session()->flash('error', 'Something went Wrong, Please try again!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['page'] = Page::where('id', $id)->first();
        return view('admin.page.create', $data);
    }

    public function update(Request $request)
    {
        
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $result = $page->delete();
        if($result)
        {
            return back()->with('success', 'Page Deleted Successfully');    
        }
        else
        {
            return back()->with('error', 'Something went Wrong, Please try again!');
        }
    }
   
}
