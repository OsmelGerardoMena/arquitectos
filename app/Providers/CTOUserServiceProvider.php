<?php

namespace App\Providers;

use Log;
use App\Models\User; 
use Carbon\Carbon; 
use Illuminate\Auth\GenericUser; 
use Illuminate\Auth\UserInterface;
use Illuminate\Contracts\Auth\Authenticatable; 
use Illuminate\Contracts\Auth\UserProvider;

class CTOUserServiceProvider implements UserProvider {

	public function register() {}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed $identifier
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($identifier) 
	{

	    $query = User::where('tbCTOUsuarioID', $identifier)->with('person');

	    if($query->count() >0)
	    {
	    	/*$person = $query->select('tbCTOUsuarioID', 'tbDirPersona.PersonaNombres')
	    		->leftJoin('tbDirPersona', 'tbDirPersonaID_CTOUsuario', '=', 'tbDirPersona.tbDirPersonaID')
	    		->first();*/
	        $user = $query->select('tbCTOUsuarioID as id', 'CTOUsuarioNombre as username', 'tbCTOUsuarioGrupoID_CTOUsuario as role', 'tbDirPersonaID_CTOUsuario', 'SecureToken', 'RegistroInactivo','RegistroCerrado')->first();
	        return $user;
	    }

	    return null;
	}

	/**
	 * Retrieve a user by by their unique identifier and "remember me" token.
	 *
	 * @param  mixed $identifier
	 * @param  string $token
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken($identifier, $token)
	{
	    // TODO: Implement retrieveByToken() method.
	    $query = User::where('tbCTOUsuarioID', $identifier)->where('remember_token','=',$token)->with('person');

        if($query->count() >0)
        {
            $person = $query->select('tbCTOUsuarioID', 'tbDirPersona.PersonaNombres')
                ->leftJoin('tbDirPersona', 'tbDirPersonaID_CTOUsuario', '=', 'tbDirPersona.tbDirPersonaID')
                ->first();
            $user = $query->select('tbCTOUsuarioID as id', 'CTOUsuarioNombre as username', 'tbCTOUsuarioGrupoID_CTOUsuario as role', 'tbDirPersonaID_CTOUsuario', 'SecureToken', 'RegistroInactivo','RegistroCerrado')->first();
            return $user;
        }

        return null;
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  string $token
	 * @return void
	 */
	public function updateRememberToken(Authenticatable $user, $token)
	{
	    // TODO: Implement updateRememberToken() method.
	    $user->setRememberToken($token);
	    $user->save();
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array $credentials
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
	    // TODO: Implement retrieveByCredentials() method.
	    
	    $hashPassword = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$credentials['password'].'}NF3oinfEFNioenf03');

	    $user = new User();
	    $user->setConnection('mysql-reader');
	    $query = $user->where(['CTOUsuarioNombre' => $credentials['username'], 'CTOUsuarioContrasena' => $hashPassword]);

	    if($query->count() > 0)
	    {
	        $user = $query->select('tbCTOUsuarioID', 'CTOUsuarioNombre', 'CTOUsuarioContrasena', 'tbCTOUsuarioGrupoID_CTOUsuario','SecureToken', 'RegistroInactivo', 'RegistroCerrado')->first();
	        
	        return $user;
	    }

	    return null;
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  array $credentials
	 * @return bool
	 */
	public function validateCredentials(Authenticatable $user, array $credentials)
	{

		$hashPassword = hash('SHA512', 'G$·ng042EWNVewkvnosv"NVi02o3{'.$credentials['password'].'}NF3oinfEFNioenf03');

		$user->setConnection('mysql-reader');

	    if($user->username() == $credentials['username'] && $user->getAuthPassword() == $hashPassword)
	    {
	        //$user->last_login_time = Carbon::now();
	        $user->save();

	        return true;
	    }
	    return false;
	}

	//public function isDeferred() {}
}