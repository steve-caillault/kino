<?php

/**
 * Tâche pour créer un utilisateur
 * php artisan user:create nickname email password permission1 permission2
 * 
 * Exemple :
 * php artisan user:create charles-dickens charles.dickens@kino.me azertyuiop admin
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
/***/
use App\Models\User;

final class CreateUserCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'user:create 
                            { nickname : Nom d\'utilisateur utilisé pour la connexion. }
                            { email : Adresse email pour contacter l\'utilisateur. }
                            { password : Mot de passe pour la connexion. }
                            { permissions* : Liste des permissions de l\'utilisateur séparés par des espaces (admin). }
    ';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Création d\'un utilisateur.';
	
	/**********************************************************************************************/
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $arguments = $this->arguments();

        // Validation
        $validator = Validator::make($arguments, [
            'nickname' => [ 'bail', 'required', 'min:5', 'max:50', 'unique:' . implode(',', [ User::class, 'nickname' ]), ],
            'email' => [ 'bail', 'required', 'min:10', 'max:100', 'email', 'unique:' . implode(',', [ User::class, 'email' ]), ],
            'password' => [ 'bail', 'required', 'min:8', 'max:100', ],
            'permissions' => [ 'bail', 'required', 'array', 'in:' . strtolower(implode(',', User::permissionsAllowed())), ],
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors()->all();
            foreach($errors as $error)
            {
                $this->error($error);
            }
            
            return Command::INVALID;
        }

        $permissions = Arr::get($arguments, 'permissions');

        // Création de l'utilisateur
        

        $user = new User();
        $user->nickname = Arr::get($arguments, 'nickname');
        $user->email = Arr::get($arguments, 'email');
        $user->password = Arr::get($arguments, 'password');
        $user->permissions = $permissions;
        
        $created = $user->save();

        if(! $created)
        {
            $this->error(sprintf('L\'utilisateur %s n\'a pas pu être créé.', $user->nickname));
            return Command::FAILURE;
        }

        $this->info(sprintf('L\'utilisateur %s a été créé.', $user->nickname));
        return Command::SUCCESS;
    }

}
