<?php


namespace App\Http\Resources\User;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->nickname,
            //如果ORM查询with关联了其他表，可以直接用$this->>whenLoaded('关联');
            //'level'     => UserLevelResource::collection($this->whenLoaded('level')),
            'level'     => new UserLevelResource($this->level)
        ];
    }
}
