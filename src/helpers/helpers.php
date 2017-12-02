<?php

use Featherwebs\Mari\Models\Image;
use Featherwebs\Mari\Models\Menu;
use Featherwebs\Mari\Models\Post;
use Featherwebs\Mari\Models\Setting;
use Featherwebs\Mari\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

function fw_setting($query)
{
    $setting = Setting::fetch($query)->first();

    if ($setting) {
        if ($setting->image) {
            return asset($setting->image->thumbnail);
        }

        return $setting->value;
    } else {
        return null;
    }
}

function fw_image($meta = null)
{
    $media = Image::query();

    if ($meta) {
        $media = $media->whereMeta($meta);
    }

    return $media->get();
}

function fw_menu($slug)
{
    return Menu::with('subMenus')->whereSlug($slug)->first();
}

function fw_post_by_tag($tags, $limit = false)
{
    $posts = Post::with('tags')->whereHas('tags', function ($q) use ($tags) {
        if (is_array($tags)) {
            $q->whereIn('slug', $tags);
        } else {
            $q->where('slug', strtolower($tags));
        }
    });

    if ($limit) {
        return $posts->take($limit);
    }

    return $posts->get();
}

function fw_post_by_category($category, $limit = false)
{

    $posts = Post::with('tags')->whereHas('postType', function ($q) use ($category) {
        if (is_array($category)) {
            $q->whereIn('slug', $category);
        } else {
            $q->where('slug', $category);
        }

    });

    if ($limit) {
        return $posts->take($limit);
    }

    return $posts->get();
}

function fw_post($slug = false)
{
    if ($slug) {
        $post = Post::with('tags')->where('slug', $slug)->first();
        if ($post) {
            return $post;
        }

        return collect([]);
    } else {
        return Post::all();
    }
}

function fw_page($slug = false)
{
    if ($slug) {
        $page = Page::where([ 'slug' => $slug ])->first();
        if ($page) {
            return $page;
        }

        return collect([]);
    } else {
        return Page::all();
    }
}

function fw_upload_image(UploadedFile $file, Model $model, $single = true, $meta = null)
{
    $extension = $file->getClientOriginalExtension();
    $filename  = $file->getClientOriginalName();
    $image     = [
        'custom' => [ 'title' => $filename ],
        'path'   => $file->storeAs(strtolower(str_plural(class_basename($model))), str_random() . '.' . $extension),
        'meta'   => str_slug($meta, '_'),
    ];

    if ($single)
    {
        if ($model->image)
        {
            $model->image->delete();
        }

        $model->image()->create($image);
    }
    else
    {
        $model->images()->create($image);
    }
}

function fw_fetch_data($url){
    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', $url);
    $data = json_decode($res->getBody());

    return $data;

}