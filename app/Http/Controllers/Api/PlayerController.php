<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
    * index function
    * 
    * Returns a JSON response with all players from the database.
    */

    public function index()
    {
        try {
            $players = Player::all();
            return response()->json(['players' => $players], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * show function
    * 
    * Takes an ID as a parameter and returns a JSON response with the team with that ID, or an error message if it doesn't exist.
    */

    public function show($id)
    {
        try {
            $player = Player::findOrFail($id);
            return response()->json(['player' => $player], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * store function
    * 
    * Validates the request input, creates a new player with the given data, and returns a JSON response with the new player's data.
    */

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'team_id' => 'required|exists:teams,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $player = Player::create([
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'photo' => $request->file('photo')->store('public/photos'),
                'team_id' => $request->input('team_id')
            ]);

            return response()->json([
                'message' => 'Player created successfully',
                'player' => $player
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * update function
    * 
    * Takes an ID and request input, validates the input, updates the player with the given ID, and returns a JSON response with the updated player's data or an error message if it doesn't exist.
    */

    public function update(Request $request, $id)
    {
        try {
            $player = Player::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'team_id' => 'required|exists:teams,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                $player = Player::find($id);

                if($player) {
                    $player->update([
                        'fname' => $request->input('fname'),
                        'lname' => $request->input('lname'),
                        'team_id' => $request->input('team_id')
                    ]);

                    return response()->json([
                        'message' => 'Player updated successfully',
                        'player' => $player
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * destroy function
    * 
    * Takes an ID as a parameter, deletes the player and returns a JSON response with a success message.
    */

    public function destroy($id)
    {
        try {
            $player = Player::findOrFail($id);
            
            $player->delete();

            return response()->json([
                'message' => 'Player deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * team function
    * 
    * Takes an ID as a parameter, finds the team with that ID, gets all players associated with that team, and returns a JSON response with the players' data or an error message if the team doesn't exist.
    */

    public function team($id)
    {
        try {
            $team = Team::findOrFail($id);
            $players = $team->players;

            return response()->json(['players' => $players], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
