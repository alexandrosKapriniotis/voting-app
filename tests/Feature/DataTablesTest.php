<?php

namespace Tests\Feature;

use App\Http\Livewire\DataTables;
use App\Http\Livewire\SearchDropdown;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DataTablesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     **/
    public function main_page_contains_datatables_livewire_component()
    {
        //$this->get('/')->assertSeeLivewire('data-tables');
    }

    /**
     * @test
     **/
    public function datatables_active_checkbox_works_correctly()
    {
        $userA = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'active'   => true,
        ]);

        $userB = User::create([
            'name' => 'User',
            'email' => 'user2@user.com',
            'password' => bcrypt('password'),
            'active'   => false,
        ]);

        Livewire::test(DataTables::class)
            ->assertSee($userA->name)
            ->set('active' , false)
            ->assertSee($userB->name);
    }

    /**
     * @test
     **/
    public function datatables_searches_name_correctly()
    {
        $userA = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'active'   => true,
        ]);

        $userB = User::create([
            'name' => 'Another',
            'email' => 'user2@user.com',
            'password' => bcrypt('password'),
            'active'   => false,
        ]);

        Livewire::test(DataTables::class)
            ->set('search' , 'user')
            ->assertSee($userA->name)
            ->assertDontSee($userB->name);
    }

    /**
     * @test
     **/
    public function datatables_searches_email_correctly()
    {
        $userA = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'active'   => true,
        ]);

        $userB = User::create([
            'name' => 'Another',
            'email' => 'another@user.com',
            'password' => bcrypt('password'),
            'active'   => false,
        ]);

        Livewire::test(DataTables::class)
            ->set('search' , 'user@user.com')
            ->assertSee($userA->name)
            ->assertDontSee($userB->name);
    }

    /**
     * @test
     **/
    public function datatables_sorts_name_asc_correctly()
    {
        $userC = User::create([
            'name' => 'Cathy C',
            'email' => 'cathy@cathy.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userA = User::create([
            'name' => 'Andre A',
            'email' => 'andre@andre.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userB = User::create([
            'name' => 'Brian B',
            'email' => 'brian@brian.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        Livewire::test(DataTables::class)
            ->call('sortBy','name')
            ->assertSeeInOrder(['Andre A', 'Brian B', 'Cathy C']);
    }

    /**
     * @test
     **/
    public function datatables_sorts_name_desc_correctly()
    {
        $userC = User::create([
            'name' => 'Cathy C',
            'email' => 'cathy@cathy.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userA = User::create([
            'name' => 'Andre A',
            'email' => 'andre@andre.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userB = User::create([
            'name' => 'Brian B',
            'email' => 'brian@brian.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        Livewire::test(DataTables::class)
            ->set('sortField','name')
            ->set('sortAsc',false)
            ->assertSeeInOrder(['Cathy C', 'Brian B', 'Andre A']);
    }

    /**
     * @test
     **/
    public function datatables_sorts_email_asc_correctly()
    {
        $userC = User::create([
            'name' => 'Cathy C',
            'email' => 'cathy@cathy.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userA = User::create([
            'name' => 'Andre A',
            'email' => 'andre@andre.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userB = User::create([
            'name' => 'Brian B',
            'email' => 'brian@brian.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        Livewire::test(DataTables::class)
            ->set('sortField','email')
            ->set('sortAsc',true)
            ->assertSeeInOrder(['andre@andre.com', 'brian@brian.com', 'cathy@cathy.com']);
    }

    /**
     * @test
     **/
    public function datatables_sorts_email_desc_correctly()
    {
        $userC = User::create([
            'name' => 'Cathy C',
            'email' => 'cathy@cathy.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userA = User::create([
            'name' => 'Andre A',
            'email' => 'andre@andre.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $userB = User::create([
            'name' => 'Brian B',
            'email' => 'brian@brian.com',
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        Livewire::test(DataTables::class)
            ->set('sortField','email')
            ->set('sortAsc',false)
            ->assertSeeInOrder(['cathy@cathy.com', 'brian@brian.com', 'andre@andre.com']);
    }
}
