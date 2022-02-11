<?php


namespace App\Observer;

use App\Models\HomeSection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeSectionObserver
{
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param  HomeSection $homeSection
	 * @return void
	 */
	public function updating(HomeSection $homeSection)
	{
		if (isset($homeSection->method) && isset($homeSection->value)) {
			// Get the original object values
			$original = $homeSection->getOriginal();
			
			if (is_array($original) && array_key_exists('value', $original)) {
				$original['value'] = jsonToArray($original['value']);
				
				// Remove old background_image from disk
				if (array_key_exists('background_image', $homeSection->value)) {
					if (
						is_array($original['value'])
						&& isset($original['value']['background_image'])
						&& $homeSection->value['background_image'] != $original['value']['background_image']
					) {
						Storage::delete($original['value']['background_image']);
					}
				}
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param  HomeSection $homeSection
	 * @return void
	 */
	public function updated(HomeSection $homeSection)
	{
		//...
	}
	
    /**
     * Listen to the Entry saved event.
     *
     * @param  HomeSection $homeSection
     * @return void
     */
    public function saved(HomeSection $homeSection)
    {
        // Removing Entries from the Cache
        $this->clearCache($homeSection);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  HomeSection $homeSection
     * @return void
     */
    public function deleted(HomeSection $homeSection)
    {
        // Removing Entries from the Cache
        $this->clearCache($homeSection);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $homeSection
     */
    private function clearCache($homeSection)
    {
		Cache::flush();
    }
}
