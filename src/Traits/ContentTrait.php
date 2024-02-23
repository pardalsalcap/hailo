<?php

namespace Pardalsalcap\Hailo\Traits;

use Illuminate\Database\Eloquent\Model;
use Pardalsalcap\Hailo\Models\Content;

trait ContentTrait
{
    public function setUrl(array|Model $values): string
    {
        if (is_array($values)) {
            $values = (object) $values;
        }
        $seo_url = config('app.url');
        if (isset($values->lang) && ! empty($values->lang)) {
            $seo_url .= '/'.$values->lang;
        }
        if (isset($values->parent_id) && ! empty($values->parent_id)) {
            $parent = Content::where('id', $values->parent_id)->where('lang', $values->lang)->first();
            if ($parent) {
                $seo_url = config('app.url').$parent->seo_url;
            } else {
                $seo_url = config('app.url').'/'.$values->lang;
            }

        }
        if (isset($values->seo_slug) && ! empty($values->seo_slug)) {
            $seo_url .= '/'.$values->seo_slug;
        }

        return $seo_url;
    }

    public function getUrl()
    {
        return config('app.url').'/'.$this->seo_url;
    }
}
