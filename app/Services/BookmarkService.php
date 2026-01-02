<?php

namespace App\Services;

use App\Models\UserSavedItem;

class BookmarkService
{
    public function saveItem($userId, $entityType, $entityId, $action = 'bookmark')
    {
        // Validate entity type
        $validTypes = ['chapter', 'topic', 'question', 'terminology', 'material'];
        if (!in_array($entityType, $validTypes)) {
            return ['success' => false, 'message' => 'Invalid entity type'];
        }
        
        // Check if item is already saved
        $existing = UserSavedItem::findByUserAndEntity($userId, $entityType, $entityId);
        
        if ($existing) {
            // Update the action if different
            if ($existing->action !== $action) {
                $existing->action = $action;
                $success = $existing->save();
                return ['success' => $success, 'message' => 'Item updated'];
            }
            
            return ['success' => true, 'message' => 'Item already saved'];
        }
        
        // Create new saved item
        $savedItem = new UserSavedItem();
        $savedItem->user_id = $userId;
        $savedItem->entity_type = $entityType;
        $savedItem->entity_id = $entityId;
        $savedItem->action = $action;
        
        $success = $savedItem->save();
        return ['success' => $success, 'message' => $success ? 'Item saved' : 'Failed to save item'];
    }

    public function removeItem($userId, $entityType, $entityId)
    {
        $savedItem = UserSavedItem::findByUserAndEntity($userId, $entityType, $entityId);
        
        if (!$savedItem) {
            return ['success' => false, 'message' => 'Item not found'];
        }
        
        $success = $savedItem->delete();
        return ['success' => $success, 'message' => $success ? 'Item removed' : 'Failed to remove item'];
    }

    public function getSavedItems($userId, $action = null)
    {
        return UserSavedItem::findByUser($userId, $action);
    }

    public function isItemSaved($userId, $entityType, $entityId, $action = 'bookmark')
    {
        $savedItem = UserSavedItem::findByUserAndEntity($userId, $entityType, $entityId, $action);
        return $savedItem !== null;
    }
}