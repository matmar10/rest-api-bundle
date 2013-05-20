<?php

namespace Lmh\Bundle\UtilBundle;
 
class EntityMerger
{
    /**
     * Merges the properties of the $newDataEntity into the $originalEntity
     *
     * @static
     * @param Object $originalEntity The source entity to merge values unto
     * @param Object $newDataEntity The new values to merge into the originalEntity
     * @return array The fields within originalEntity updated as part of the merge
     */
    public static function merge(&$originalEntity, &$newDataEntity)
    {

        // keep track of which fields actually were updated
        $updatedFields = array();

        // TODO: this method of merging sucks; figure out something better or at least move to service
        $updateData = (array)$newDataEntity;
        foreach($updateData as $field => $v) {

            // TODO: Understand a proper way to recover protected field names since they have weird characters around them
            $trimmed = substr($field, 3, strlen($field)-3);

            $getterFunctionName = 'get' . ucwords($trimmed);


            if(is_null($newDataEntity->$getterFunctionName())) {
                continue;
            }

            // ignore if update isn't actually needed
            if($originalEntity->$getterFunctionName() == $newDataEntity->$getterFunctionName()) {
                continue;
            }

            // update the original entity with the new field data
            $setterFunctionName = 'set' . ucwords($trimmed);
            $originalEntity->$setterFunctionName($newDataEntity->$getterFunctionName());

            // add this field to the list of what's been updated
            $updatedFields[] = $trimmed;
        }

        return $updatedFields;
    }
}
