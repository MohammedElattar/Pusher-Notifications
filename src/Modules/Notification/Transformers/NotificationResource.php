<?php

namespace Modules\Notification\Transformers;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->data;

        if ($data['shouldTranslateMessage'] ?? false) {
            $data['message'] = translate_word($data['message']);
        }

        unset($data['shouldTranslateMessage']);

        return [
            'id' => $this->id,
            'createdAt' => DateHelper::dateDiffForHumans($this->created_at),
            'seen' => ! is_null($this->read_at),
            'body' => $data,
        ];
    }
}
