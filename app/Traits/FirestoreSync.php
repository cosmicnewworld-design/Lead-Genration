<?php

namespace App\Traits;

use Google\Cloud\Firestore\DocumentReference;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait FirestoreSync
 * 
 * Allows Eloquent models to sync with Firestore automatically.
 * Use with caution in production—consider using event listeners for better control.
 * 
 * Usage:
 *     class Lead extends Model {
 *         use FirestoreSync;
 *         protected $firestoreCollection = 'leads';
 *     }
 */
trait FirestoreSync
{
    /**
     * Firestore collection name (override in model)
     */
    protected $firestoreCollection = 'models';

    /**
     * Get the Firestore document reference for this model
     */
    public function firestoreDoc(): DocumentReference
    {
        $db = app('firestore');
        return $db->collection($this->firestoreCollection)->document((string)$this->id);
    }

    /**
     * Sync current model to Firestore
     */
    public function syncToFirestore(): void
    {
        $this->firestoreDoc()->set($this->firestoreArray(), ['merge' => true]);
    }

    /**
     * Get Firestore snapshot
     */
    public function getFirestoreSnapshot()
    {
        return $this->firestoreDoc()->snapshot();
    }

    /**
     * Check if document exists in Firestore
     */
    public function existsInFirestore(): bool
    {
        return $this->getFirestoreSnapshot()->exists();
    }

    /**
     * Delete from Firestore
     */
    public function deleteFromFirestore(): void
    {
        $this->firestoreDoc()->delete();
    }

    /**
     * Get array representation for Firestore (exclude internal fields)
     */
    protected function firestoreArray(): array
    {
        return array_merge(
            $this->toArray(),
            [
                'created_at' => $this->created_at?->toDateTime(),
                'updated_at' => $this->updated_at?->toDateTime(),
            ]
        );
    }

    /**
     * Boot the trait—auto-sync on save (optional)
     */
    protected static function bootFirestoreSync(): void
    {
        // Uncomment to auto-sync on save:
        // static::saved(function ($model) {
        //     $model->syncToFirestore();
        // });

        // Uncomment to auto-delete from Firestore:
        // static::deleted(function ($model) {
        //     $model->deleteFromFirestore();
        // });
    }
}
