<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Room extends Model
{
    /**
     * DB table this model uses
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * Fields to be protected from mass assignment
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Declare a relationship between this room and the subjects
     * that are allowed to use this room
     *
     * @return Illuminate\Database\Eloquent
     */
    // public function subjects()
    // {
    //     return $this->belongsToMany(Subject::class, 'favourite_rooms', 'room_id', 'subject_id');
    // }


    public function loadRooms()
    {
        $columns = [
            'a.id',
            'a.name as room',
            'a.lab'
        ];
        $rooms = DB::table('rooms AS a')->select($columns)->get()->toArray();

        return $rooms;
    }
}