<?php

namespace App\Controller;

use App\Entity\File;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
	private $om;

	public function __construct(ObjectManager $objectManager)
	{
		$this->om = $objectManager;
	}

	/**
	 * @Route("/upload", name="upload_file")
	 */
	public function upload(Request $request)
	{
		if($request->isMethod(Request::METHOD_GET)){
			return new Response('<form method="post" enctype="multipart/form-data">
	<input type="file" name="file" />
	<input type="submit" />
</form>');
		}

		$responseData = [
			'errors' => [],
			'data' => null,
		];

		/** @var UploadedFile $uploadedFile */
		$uploadedFile = $request->files->get('file');
		if(!$uploadedFile->isValid()){
			$responseData['errors'][] = $uploadedFile->getErrorMessage();
			return $this->json($responseData);
		}

		$file = new File();
		$file->setName($uploadedFile->getClientOriginalName());
		$file->setMimetype($uploadedFile->getClientMimeType());
		$file->setExtension($uploadedFile->getClientOriginalExtension());

		$this->om->persist($file);
		$this->om->flush();

		try{
			$uploadedFile->move(
				'uploads',
				$file->getId()
			);
			$responseData['data'] = $file->getId();
		}catch(\Exception $e){
			$responseData['errors'][] = $e->getMessage();
			$this->om->remove($file);
			$this->om->flush();
		}


		return $this->json($responseData);
	}

	/**
	 * @Route("/{id}", name="download_file")
	 */
	public function download($id)
	{
		$file = $this->om->getRepository(File::class)->find($id);

		return $this->file('uploads/'.$id, $file->getName());
	}
}
