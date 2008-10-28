<?php
/**
* Pridava modelku
*/
class ProcessorAddModel extends LBoxFormProcessor
{
	public function process() {
		try {
			// soutezici
			$model = new ModelsRecord($this->form->getControlByName("email")->getValue());
			$model->name		= $this->form->getControlByName("name")->getValue();
			$model->surname		= $this->form->getControlByName("surname")->getValue();
			$model->ref_school	= $this->form->getControlByName("ref_school")->getValue();
			$model->store();

			// fotka
			$valueFiles			= $this->form->getControlByName("photo")->getValueFiles(); 
			$photo				= new PhotosRecord();
			$photo->setFileName($valueFiles["name"]); 
			$photo->ref_model	= $model->email;
			$photo->saveUploadedFile($valueFiles["tmp_name"], $valueFiles["name"], $valueFiles["size"]);
			// osetrit maximalni velikost obrazku
			$photo->limitSize(LBoxConfigManagerProperties::getPropertyContentByName("model_photo_max_size_longer"));
			// vytvorit nahled
			if ($photo->sizeX > $photo->sizeY) {
				$photo->createThumbnail(LBoxConfigManagerProperties::getPropertyContentByName("model_thumbnail_size_longer"));
			}
			else {
				$photo->createThumbnail(0, LBoxConfigManagerProperties::getPropertyContentByName("model_thumbnail_size_longer"));
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>