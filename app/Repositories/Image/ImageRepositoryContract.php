<?php
namespace App\Repositories\Image;
 
interface ImageRepositoryContract
{
    
    public function upload( $form_data );

    public function createUniqueFilename( $filename, $extension );

    public function original( $photo, $filename );

    public function icon( $photo, $filename );

    public function delete( $filename );
    
}