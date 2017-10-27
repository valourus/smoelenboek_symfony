<?php
/**
 * Created by PhpStorm.
 * User: koenvanderels
 * Date: 10/19/2017
 * Time: 2:52 PM
 */

namespace AppBundle\Namer;


use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class UserPhotoNamer implements NamerInterface {

    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object
     *
     * @return string The file name
     */
    public function name($object, PropertyMapping $mapping) {
        $file = $object->getImageFile();
        $extension = $file->getMimeType();
        $extension = preg_split('~/~', $extension);
        return uniqid('', true). '.' . $extension[1];
    }
}