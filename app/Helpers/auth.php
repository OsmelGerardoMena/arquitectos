<?php

if (!function_exists('auth_permissions'))
{
	/**
	 * Test
	 *
	 * @param int - user id
	 * @return object
	 */
	function auth_permissions($id) {

		if (Auth::check()) {

			$roles = DB::connection('mysql-reader')->table('tbCTOUsuarioGrupo')->where('tbCTOUsuarioGrupoID', $id)->first();
			$permissions = json_decode($roles->CTOUsuarioPermisos);

			return $permissions;
		}
	}


	/**
	 * Get id role
	 *
	 * @param int - user id
	 * @return object
	 */
	function auth_permissions_id($id) {

		if (Auth::check()) {

			$roles = DB::connection('mysql-reader')->table('tbCTOUsuarioGrupo')->where('tbCTOUsuarioGrupoID', $id)->first();
			$permissions = $roles->tbCTOUsuarioGrupoID;

			return $permissions;
		}
	}

    /**
     * Get id role
     *
     * @param int - user id
     * @return object
     */
    function auth_permissions_data($id) {

        if (Auth::check()) {

            $role = DB::connection('mysql-reader')->table('tbCTOUsuarioGrupo')->where('tbCTOUsuarioGrupoID', $id)->first();
            return $role;
        }
    }


}

