<?php
namespace onix\telegram\entities;

/**
 * Class UserProfilePhotos
 *
 * @link https://core.telegram.org/bots/api#userprofilephotos
 *
 * @property-read int $totalCount Total number of profile pictures the target user has
 * @property-read PhotoSize[][] $photoSets;
 */
class UserProfilePhotos extends Entity
{
    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['photos', 'totalCount'];
    }

    /**
     * @inheritDoc
     */
    protected function subEntities()
    {
        return [
            'photos' => PhotoSize::class,
        ];
    }

    /**
     * Requested profile pictures (in up to 4 sizes each)
     *
     * This method overrides the default getPhotos method and returns a nice array
     *
     * @return PhotoSize[][]
     */
    public function getPhotoSets()
    {
        $all_photos = [];

        if ($these_photos = $this->getAttribute('photos')) {
            foreach ($these_photos as $photos) {
                $all_photos[] = array_map(function ($photo) {
                    return new PhotoSize($photo);
                }, $photos);
            }
        }

        return $all_photos;
    }
}
