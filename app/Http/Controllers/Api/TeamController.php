<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
    * unauthResponse function use for response message to unatheticated users 
    * 
    */

    public function unauthResponse()
    {
        try {
            return response()->json([
                'status' => false,
                'message' => 'You are unautheticated. redirect to login page'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testSafe()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Passed'
            ]);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
    * index function
    * 
    * Returns a JSON response with all teams from the database.
    */

    public function index()
    {
        try {
            $teams = Team::all();
            return response()->json(['teams' => $teams], 200);
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
            $team = Team::findOrFail($id);
            return response()->json(['team' => $team], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * store function
    * 
    * Validates the request input, creates a new team with the given data, and returns a JSON response with the new team's data.
    */

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'logo' => 'required|image|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $team = Team::create([
                'name' => $request->input('name'),
                'logo' => $request->file('logo')->store('public/logos'),
            ]);

            return response()->json([
                'message' => 'Team created successfully',
                'team' => $team
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * update function
    * 
    * Takes an ID and request input, validates the input, updates the team with the given ID, and returns a JSON response with the updated team's data or an error message if it doesn't exist.
    */

    public function update(Request $request, $id)
    {
        try {
            $team = Team::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            else
            {
                $team = Team::find($id);
                
                if($team)
                {
                    $team->update([
                        'name' => $request->input('name'),
                    ]);
                    
                    return response()->json([
                        'message' => 'Team Updated successfully',
                        'team' => $team
                    ], 200);
                }
            }    
        }catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * destroy function
    * 
    * Takes an ID as a parameter, deletes the team with that ID and all associated players, and returns a JSON response with a success message.
    */

    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);

            $players = Player::where('team_id', $team->id)->get();
            foreach ($players as $player) {
                $player->update(['team_id' => null]);
            }

            $team->delete();

            return response()->json([
                'message' => 'Team deleted successfully',
                'team' => $team
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
    * players function
    * 
    * Takes an ID as a parameter, finds the team with that ID, gets all players associated with that team, and returns a JSON response with the players' data or an error message if the team doesn't exist.
    */

    public function players($id)
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
