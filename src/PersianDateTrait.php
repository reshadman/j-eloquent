<?php namespace Bigsinoos\JEloquent;

use Illuminate\Support\Str;
use Miladr\Jalali\jDate;

trait PersianDateTrait {

    /**
     * Convert Gregorian date to Jalali date
     *
     * @param string $what
     * @param string $format
     * @return null
     */
    public function convertToPersian($what = 'created_at', $format = null)
    {
        $format = is_null($format) ? $this->getJalaliFormat() : $format;
        
        if (is_null($this->$what)) {
            return null;
        }
        
        return jDate::forge($this->$what)->format($format);
    }

    /**
     * Add persian dates to model's toJson, toArray, __toString
     *
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();
        
        if (!$this->appendsJalaliByDefault()) {
            return $arr;
        }
        
        foreach($this->getDates() as $date){
            $key = "{$this->getJalaliPrefix()}{$date}";
            $arr[$key] = $this->convertToPersian($date);
        }
        return $arr;
    }

    /**
     * Get jalali dates based on model
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        $prefix = $this->getJalaliPrefix();
        if (Str::startsWith($key, $prefix)){
            $parentKey = str_replace($prefix, '', $key);
            $parent = $this->$parentKey;
            return is_null($parent) ? null : $this->convertToPersian($parentKey);
        }
        return parent::__get($key);
    }

    /**
     * Get jalali prefix for accessing dates
     *
     * @return string
     */
    protected function getJalaliPrefix()
    {
        if (isset($this->jalaliPrefix)) {
            return $this->jalaliPrefix;
        }

        return 'jalali_';
    }

    /**
     * Get jalali format
     *
     * @return string
     */
    protected function getJalaliFormat()
    {
        if (isset($this->jalaliDateFormat)) {
            return $this->jalaliDateFormat;
        }

        return 'y/m/d';
    }

    /**
     * Set jalali format
     *
     * @param $format
     * @return $this
     */
    public function setJalaliFormat($format)
    {
        $this->jalaliDateFormat = $format; return $this;
    }
    
    /**
     * Appends dates by default
     * 
     * @return bool
     */
    protected function appendsJalaliByDefault()
    {
        if (isset($this->appendsJalaliByDefault)) {
            return (bool)$this->appendsJalaliByDefault;
        }

        return true;
    }

    /**
     * Don't append jalali by default
     *
     * @return $this
     */
    public function doNotAppendJalaliByDefault()
    {
        $this->appendsJalaliByDefault = false;
        return $this;
    }
} 
