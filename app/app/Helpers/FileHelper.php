<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileHelper {

	private const SVG_PATH = '/resources/svg';
	private const ICONS_PATH = self::SVG_PATH . '/icons';

	/**
	 * Check if a file exists in the storage
	 */
	public static function exists(string $path): bool {
		return Storage::disk('public')->exists($path);
	}

	/**
	 * Delete a file from the storage
	 */
	public static function delete(string $path): bool {
		return Storage::disk('public')->delete($path);
	}

	/**
	 * Get the path of a file in the public assets directory
	 */
	public static function publicStoragePath(?string $path = ''): string {
		return storage_path('app/public/' . $path);
	}

	/**
	 * Get the versioned path of a file in the public storage directory
	 */
	public static function publicAsset(string $path): string {
		$publicFilePath = asset('storage/' . $path);
		$storedFilePath = base_path() . '/public/storage/' . $path;
		$fileVersion = file_exists($storedFilePath) ? filemtime($storedFilePath) : 0;

		return $publicFilePath . '?v=' . $fileVersion;
	}

	/**
	 * Move a file to the public storage directory
	 */
	public static function uploadFile(UploadedFile $file, string $filePath): bool {
		$fileDirectory = ArrayHelper::explodeLast('/', $filePath)[0];
		$fileName = ArrayHelper::explodeLast('/', $filePath)[1];

		if (Storage::disk('public')->exists($filePath)) {
			Storage::disk('public')->delete($filePath);
		}

		$storedFile = $file->storeAs($fileDirectory, $fileName, 'public');

		return (bool) $storedFile;
	}

	/**
	 * Upload an image
	 */
	public static function uploadImage(UploadedFile $file, string $filePath, ?int $size = null, string $mode = 'min', int $quality = 80): bool {
		$fileDirectory = ArrayHelper::explodeLast('/', $filePath)[0];
		$fileName = ArrayHelper::explodeLast('/', $filePath)[1];
		$type = explode_last('.', $fileName)[1];

		// Get image data
		$srcImg = $file->getPathname();
		$srcType = exif_imagetype($srcImg);
		$srcResolution = getimagesize($srcImg);
		$srcWidth = $srcResolution[0] ?? 0;
		$srcHeight = $srcResolution[1] ?? 0;

		// Create the storage directory if it doesn't exist
		if (!Storage::directoryExists('public/' . $fileDirectory)) {
			Storage::makeDirectory('public/' . $fileDirectory);
		}

		// Calculate the new dimensions based on the mode
		$x = 0;
		$y = 0;
		$ratio = $srcWidth / $srcHeight;

		switch ($mode) {
			case 'width':
				$newWidth = $size;
				$newHeight = $size / $ratio;
				break;

			case 'height':
				$newWidth = $size * $ratio;
				$newHeight = $size;
				break;

			case 'max':
				if ($ratio > 1) {
					$newWidth = $size;
					$newHeight = $size / $ratio;
				} else {
					$newWidth = $size * $ratio;
					$newHeight = $size;
				}
				break;

			case 'min':
				if ($ratio > 1) {
					$newWidth = $size * $ratio;
					$newHeight = $size;
				} else {
					$newWidth = $size;
					$newHeight = $size / $ratio;
				}
				break;

			case 'crop':
				$newWidth = $newHeight = $size;
				$new_ratio = 1;

				if ($ratio > $new_ratio) {
					$srcWidth = $srcHeight * $new_ratio;
					$x = ($srcResolution[0] - $srcWidth) / 2;
				} else {
					$srcHeight = $srcWidth / $new_ratio;
					$y = ($srcResolution[1] - $srcHeight) / 2;
				}
				break;
		}

		// Start the image creation
		$tempImg = imagecreatetruecolor($newWidth, $newHeight);

		switch ($srcType) {
			case IMAGETYPE_WEBP:
				$originalImg = imagecreatefromwebp($srcImg);
				break;
			case IMAGETYPE_PNG:
				$originalImg = imagecreatefrompng($srcImg);
				break;
			case IMAGETYPE_JPEG:
				$originalImg = imagecreatefromjpeg($srcImg);
				break;
			default:
				$originalImg = imagecreatefromstring(file_get_contents($srcImg));
		}

		// Save the new image
		switch ($type) {
			case 'webp':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagewebp($tempImg, public_storage($filePath), $quality);

				break;

			case 'jpg':
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagejpeg($tempImg, public_storage($filePath), $quality);

				break;

			case 'png':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagepng($tempImg, public_storage($filePath));

				break;
		}

		imagedestroy($originalImg);
		imagedestroy($tempImg);

		// Return the success of the operation
		return self::exists($filePath);
	}


	/**
	 * Import an icon and embed it in the HTML
	 */
	public static function embedSvg(string $fileName, array $customClasses = [], bool $isIcon = false): string {
		$iconDefault = [
			'rounded', 'filled', '400', 'icon'
		];

		// Replace dots with slashes
		$fileName = str_replace('.', '/', $fileName);

		// Add the default icon directories
		if ($isIcon) {
			$fileName = explode('/', $fileName);
			$toFill = count($iconDefault) - count($fileName);

			for ($i = 0; $i < $toFill; $i++) {
				array_unshift($fileName, $iconDefault[$toFill - $i - 1]);
			}

			$fileName = implode('/', $fileName);
		}

		// Add the default class and any custom classes
		$classes = implode(' ', ['svg-icon', ...$customClasses]);

		// Get the icon file
		$fileDirectory = $isIcon ? self::ICONS_PATH : self::SVG_PATH;
		$filePath = base_path() . $fileDirectory . '/' . $fileName . '.svg';

		// Get the icon content
		try {
			$file = file_get_contents($filePath);

			// Add the classes to the icon and return it
			return str_replace('<svg', '<svg class="' . $classes . '"', $file);
		} catch (\Exception $e) {
			if (env('APP_DEBUG')) {
				dd('Icon not found: ' . $fileName . ')');
			}

			Log::error('Icon not found: ' . $fileName . ')');
		}

		return '';
	}
}
