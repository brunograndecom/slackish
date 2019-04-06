<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'google_token',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'avatar_path',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentChannel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param string $companyName
     */
    public function createCompany(string $companyName)
    {
        $company = \App\Company::where('name', '=', $companyName)->first();

        if ( $company !== null ) {

            $channel  = $company->defaultChannel()->first();

        } else {
            $company = $this->currentCompany()->create([
               'name' => $companyName,
               'owner_id' => $this->id
            ]);

            $channel = $company->channels()->create([
                'name' => 'general',
            ]);

            $company->update([
                'default_channel_id' => $channel->id,
            ]);
        }

        $this->currentCompany()->associate($company);
        $this->currentChannel()->associate($channel);
        $this->save();
    }

    /**
     * @param \App\Channel $channel
     *
     * @return bool
     */
    public function canJoin(Channel $channel)
    {
        return $this->currentCompany->is($channel->company);
    }

    /**
     * @param \App\Channel $channel
     */
    public function joinChannel(Channel $channel)
    {
        $this->currentChannel()->associate($channel);
        $this->save();
    }
}
