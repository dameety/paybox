<?php
namespace Dameety\Paybox\Tests\Feature;

use Dameety\Paybox\Models\Error;
use Dameety\Paybox\Tests\TestCase;

class ErrorTest extends TestCase
{
    /** @test */
    public function can_get_all_errors()
    {
        Error::create([
            'user' => 1,
            'name' => 'the name of the error',
            'message' => 'the message of the error'
        ]);

        $errors = Error::all();
        $error = $errors[0];

        $response = $this->get(route('errors.index'));

        $response   ->assertStatus(200)
                    ->assertSee($error->user)
                    ->assertSee($error->name)
                    ->assertSee($error->message);
    }

    /** @test */
    public function canDeleteErors()
    {
        $error = Error::create([
            'user' => 1,
            'name' => 'the name of the error',
            'message' => 'the message of the error',
            'slug' => 'the-name-of-the-error'
        ]);

        $response = $this->delete('/ajax/error/' . $error->slug . '/delete');

        $response   ->assertStatus(200)
                    ->assertJson(['deleted' => true]);

        $error = Error::findBySlug(($error->slug));
        $this->assertEquals($error, null);
    }

    /** @test */
    public function can_get_correct_error_total()
    {
        Error::create([
            'user' => 1,
            'name' => 'the name of the error',
            'message' => 'the message of the error',
            'slug' => 'the-name-of-the-error'
        ]);

        Error::create([
            'user' => 1,
            'name' => 'the name of the error',
            'message' => 'the message of the error',
            'slug' => 'the-name-of-the-error2'
        ]);

        Error::create([
            'user' => 1,
            'name' => 'the name of the error',
            'message' => 'the message of the error',
            'slug' => 'the-name-of-the-error3'
        ]);

        $total = Error::all()->count();
        $this->assertEquals($total, 3);
    }
}