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
    
        if($request->slug == 'home')
        {

            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'button_text' => 'required',
                'button_link' => 'required',
                'image' => 'required_if:image_old,""',
                'used_by_section_image' => 'required_if:used_by_section_image_old,""',
                'client_banner' => 'required_if:client_banner_old,""',
                'client_title' => 'required',
                'client_description' => 'required',
                'title_1' => 'required',
                'description_1' => 'required',
                'title_2' => 'required',
                'description_2' => 'required',
                'title_3' => 'required',
                'description_3' => 'required',
                'trusted_brand_title' => 'required',
                'trusted_brand_description' => 'required',
                'popular_service' => 'required',
            ]);


            $arr['hero']=[
                'title' => $request->title,
                'description' => $request->description,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'image'       => !empty($request->hasfile('image')) ? $this->homepageImage($request->file('image')) : (($request->image_old) ? $request->image_old : ''),
                // 'image'       => !empty($request->hasfile('image')) ? $this->homepageImage($request->file('image')) : '',
            ];

            // if($request->hasfile('image')){
            //     $arr['hero']['image'] = !empty($request->hasfile('image')) ? $this->homepageImage($request->file('image')) : (($request->image_old) ? $request->image_old : '');
            // }


// dd();
            // if($request->hasfile('used_by_section_image')){
                $images_used_section = $request->file('used_by_section_image');
                // echo "<pre>";
                // dd(json_encode($request->used_by_section_image_old));
                // print_r(array_merge($request->used_by_section_image_old,$images_used_section));
                // dd($images_used_section);
                $used_by_section_image_new = [];
                $used_by_section_image = [];

                // print_r($request->used_by_section_image_old);
                // print_r(array_keys($request->used_by_section_image_old));
                // $finalImage[] = $request->used_by_section_image_old;

                if(!empty($images_used_section)){
                    foreach ($images_used_section as $key => $value) {
                        // print_r(in_array($key, array_keys($request->used_by_section_image_old)));
                        // print_r($key);
                        // print_r($value);
                        $used_by_section_image_new[$key] = !empty($value) ? $this->homepageImage($value) :  '';
                        // array_push($used_by_section_image, !empty($value) ? $this->homepageImage($value) : (($request->used_by_section_image_old) ? $request->used_by_section_image_old : ''));
                        
                    }
                }
                $used_by_section_image = $used_by_section_image_new + $request->used_by_section_image_old;
                ksort($used_by_section_image);
                // print_r($used_by_section_image);

                $arr['used_by']=[
                    'used_by_section_image' => $used_by_section_image,
                ];
                // dd($arr);
            // }
            // else{
            //     $arr['used_by']=[
            //         'used_by_section_image' => $request->used_by_section_image_old,
            //     ];
            // }

            $arr['trusted_brand_title'] = $request->trusted_brand_title;
            $arr['trusted_brand_description'] = $request->trusted_brand_description;

            foreach ($request->trusted_brands as $value) {

                $arr['trusted_brands'][] = [
                    'brand_description' => $value['brand_description'],
                    'brand_name' => $value['brand_name'],
                    'designation' => $value['designation'],
                    'total_projects' => $value['total_projects'],
                    'launch_projects' => $value['launch_projects'],
                    'logo' => !empty($value['logo']) ? $this->homepageImage($value['logo']) : (($value['logo_old']) ? $value['logo_old'] : ''),
                ];
                // print_r($value);

            }

            

            // $arr['trusted_brands'] = $request->trusted_brands;

            // $total_brands = count($request->trusted_brands);
            // $count=0;
            // foreach ($arr['trusted_brands'] as $value) {
            //     print_r($value['logo']);

            // $value['logo'] = !empty($request->hasfile($value['logo'])) ? $this->homepageImage($request->file($value['logo'])) : '';
            // // $count++;
            // }

            // print_r($arr['trusted_brands']);

            // $arr['trusted_brands']=[
            //     // 'logo' => $logo,
            //     'brand_description' => $request->brand_description,
            //     'brand_name' => $request->brand_name,
            //     'designation' => $request->designation,
            //     'total_projects' => $request->total_projects,
            //     'launch_projects' => $request->launch_projects,
            // ];

            // if($request->hasfile('logo')){
            //     $logo_trusted_brands = $request->file('logo');
            //     $logo = [];
            //     foreach ($logo_trusted_brands as $value) {
            //         array_push($logo, !empty($value) ? $this->homepageImage($value) : '');
            //     }
            //     $arr['trusted_brands']['logo'] = $logo;

            // }

            $arr['for_client']=[
                // 'client_banner' => !empty($request->hasfile('client_banner')) ? $this->homepageImage($request->file('client_banner')) : '',
                'client_banner' => !empty($request->hasfile('client_banner')) ? $this->homepageImage($request->file('client_banner')) : (($request->client_banner_old) ? $request->client_banner_old : ''),
                'client_title' => $request->client_title,
                'client_description' => $request->client_description,
                'title_1' => $request->title_1,
                'description_1' => $request->description_1,
                'title_2' => $request->title_2,
                'description_2' => $request->description_2,
                'title_3' => $request->title_3,
                'description_3' => $request->description_3,
            ];

            // if($request->hasfile('client_banner')){
            //     $arr['for_client']['client_banner'] = !empty($request->hasfile('client_banner')) ? $this->homepageImage($request->file('client_banner')) : (($request->client_banner_old) ? $request->client_banner_old : '');
            // }

            $arr['popular_service'] = $request->popular_service;
            
            // print_r(json_encode($arr));


            // dd($request->all());


            $page = Page::where('id', $request->id)->update(['content' => json_encode($arr),]);

            if($page)
            {
                session()->flash('success','Page Updated successfully');
                return redirect()->route('admin.page.index');
            }
            else
            {
                session()->flash('error', 'Something went Wrong, Please try again!');
                return redirect()->back();
            }

        }
        else
        {
            $request->validate([
                'title' => 'required | string',
            ]);

            if(empty($request->slug)){
                $request['slug'] = Str::slug($request->title);
                $request['new_slug'] = $request['slug'];

                $count=1;
                while(Page::where('slug', '=', $request['new_slug'])->exists())
                    {
                        $request['new_slug'] = $request['slug'].'-'.$count;
                        $count++;
                    }
            }
            else
            {
                $request['new_slug'] = $request->slug;
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
    }

    public function edit($id)
    {
        $data['page'] = Page::where('id', $id)->first();

        if($data['page']->slug == 'home')
        {
            return view('admin.page.homepage',$data);
        }
        else
        {
            return view('admin.page.create', $data);
        }
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
        if($page->slug == 'home')
        {
            return back()->with('error', 'homepage cannot be deleted' );
        }
        else
        {
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
   
}
