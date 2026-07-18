<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Purchase;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;


class FunctionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_name_required_shows_validation_message()
    {
        $response = $this->followingRedirects()
        ->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',

        ]);

        $response->assertSee('お名前を入力してください');
    }

    public function test_email_required_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/register', [
                'name' => 'テスト太郎',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('メールアドレスを入力してください');
    }

    public function test_password_required_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/register', [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSee('パスワードを入力してください');
    }

    public function test_password_min_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/register', [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => 'passwor',
                'password_confirmation' => 'passwor',
            ]);

        $response->assertSee('パスワードは8文字以上で入力してください');
    }

    public function test_password_confirmed_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/register', [
                'name' => 'テスト太郎',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password122',
            ]);

        $response->assertSee('パスワードと一致しません');
    }

    public function test_user_can_register_and_redirect_to_profile()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_login_email_required_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/login', [
                'password' => 'password123',
            ]);

        $response->assertSee('メールアドレスを入力してください');
    }

    public function test_login_password_required_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/login', [
                'email' => 'test@example.com',
            ]);

        $response->assertSee('パスワードを入力してください');
    }

    public function test_login_with_invalid_credentials_shows_validation_message()
    {
        $response = $this->followingRedirects()
            ->post('/login', [
                'email' => 'test@example.com',
                'password' => 'password123',
            ]);

        $response->assertSee('ログイン情報が登録されていません');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
    }

    public function test_all_items_are_displayed()
    {
        $item1 = Item::factory()->create([
            'item_name' => '腕時計',
        ]);

        $item2 = Item::factory()->create([
            'item_name' => 'HDD',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertSee('HDD');
    }

    public function test_sold_label_is_displayed_for_purchased_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Sold');
    }

    public function test_user_cannot_see_own_items_in_item_list()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'item_name' => '自分の商品',
        ]);

        Item::factory()->create([
            'user_id' => $otherUser->id,
            'item_name' => '他人の商品',
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }

    public function test_only_liked_items_are_displayed_in_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => 'いいねした商品',
        ]);

        $notLikedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => 'いいねしていない商品',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    public function test_sold_label_is_displayed_for_purchased_item_in_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Sold');
    }

    public function test_no_items_are_displayed_when_guest_opens_mylist()
    {
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('腕時計');
    }

    public function test_can_search_items_by_partial_name()
    {
        Item::factory()->create([
            'item_name' => '腕時計',
        ]);

        Item::factory()->create([
            'item_name' => '腕輪',
        ]);

        Item::factory()->create([
            'item_name' => 'HDD',
        ]);

        $response = $this->get('/?keyword=腕');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('腕輪');
        $response->assertDontSee('HDD');
    }

    public function test_search_keyword_is_preserved_on_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $watch = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $hdd = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => 'HDD',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $watch->id,
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $hdd->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/?tab=mylist&keyword=腕');

        $response->assertStatus(200);

        $response->assertSee('value="腕"', false);

        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_all_required_item_details_are_displayed()
    {
        $seller = User::factory()->create([
            'name' => '出品者ユーザー',
        ]);

        $commentUser = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $likeUser1 = User::factory()->create();
        $likeUser2 = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'image' => 'items/watch.jpg',
            'item_name' => '腕時計',
            'brand_name' => 'Rolax',
            'price' => 15000,
            'item_description' => '高級感のある腕時計です',
        ]);

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        Like::create([
            'user_id' => $likeUser1->id,
            'item_id' => $item->id,
        ]);

        Like::create([
            'user_id' => $likeUser2->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'comment' => 'とても素敵な商品ですね',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('storage/items/watch.jpg', false);

        $response->assertSee('腕時計');
        $response->assertSee('Rolax');
        $response->assertSee('15,000');
        $response->assertSee('高級感のある腕時計です');

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee($item->condition->name);

        $response->assertSee('2');

        $response->assertSee('1');

        $response->assertSee('コメントユーザー');
        $response->assertSee('とても素敵な商品ですね');
    }

    public function test_multiple_categories_are_displayed_on_item_detail()
    {
        $item = Item::factory()->create([
            'item_name' => '腕時計',
        ]);

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $category3 = Category::create([
            'name' => 'アクセサリー',
        ]);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
            $category3->id,
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee('アクセサリー');
    }

    public function test_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/like');

        $response->assertStatus(200);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertSee('腕時計');
        $response->assertSee('1');
    }

    public function test_like_icon_changes_after_liking_item()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/like');

        $response->assertStatus(200);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertSee('ハートロゴ_ピンク.png');
    }

    public function test_user_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/like');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertSame(0, $item->fresh()->likes()->count());

        $response->assertSee('0');
    }

    public function test_authenticated_user_can_submit_comment()
    {
        $user = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $this->assertSame(0, $item->comments()->count());

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/item/' . $item->id . '/comment', [
                'comment' => 'とても素敵な商品ですね',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'とても素敵な商品ですね',
        ]);

        $this->assertSame(1, $item->fresh()->comments()->count());

        $response->assertSee('とても素敵な商品ですね');

        $response->assertSee('コメントユーザー');
    }

    public function test_guest_cannot_submit_comment()
    {
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->post('/item/' . $item->id . '/comment', [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'ゲストコメント',
        ]);

        $this->assertSame(0, $item->fresh()->comments()->count());
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'comment' => '',
            ]);

        $response->assertRedirect('/item/' . $item->id);

        $response->assertSessionHasErrors('comment');

        $this->assertSame(0, $item->fresh()->comments()->count());
    }

    public function test_comment_validation_for_over_255_characters()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $comment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'comment' => $comment,
            ]);

        $response->assertRedirect('/item/' . $item->id);

        $response->assertSessionHasErrors('comment');

        $this->assertDatabaseMissing('comments', [
            'comment' => $comment,
        ]);
    }

    public function test_user_can_purchase_item()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $response = $this->actingAs($buyer)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'card',
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold_on_item_list()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $purchaseResponse = $this->actingAs($buyer)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'card',
            ]);

        $purchaseResponse->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($buyer)->get('/');

        $response->assertStatus(200);

        $response->assertSee('腕時計');

        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_on_profile_purchase_list()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $purchaseResponse = $this->actingAs($buyer)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'card',
            ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($buyer)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);

        $response->assertSee('腕時計');
    }

    public function test_selected_payment_method_can_be_submitted()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->actingAs($buyer)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'convenience_store',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル101',
            ]);

        $response->assertSessionDoesntHaveErrors('payment_method');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_changed_address_is_displayed_on_purchase_page()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        Profile::create([
            'user_id' => $buyer->id,
            'image' => 'profiles/default-user.png',
            'name' => '購入ユーザー',
            'postal_code' => '111-1111',
            'address' => '東京都新宿区1-1-1',
            'building' => '旧住所マンション101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $response = $this->actingAs($buyer)
            ->post('/purchase/address/' . $item->id, [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区2-2-2',
                'building' => 'テストビル202',
            ]);

        $response->assertRedirect('/purchase/' . $item->id);

        $response = $this->actingAs($buyer)
            ->get('/purchase/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区2-2-2');
        $response->assertSee('テストビル202');
    }

    public function test_changed_shipping_address_is_saved_with_purchase()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        Profile::create([
            'user_id' => $buyer->id,
            'image' => 'profiles/default-user.png',
            'name' => '購入ユーザー',
            'postal_code' => '111-1111',
            'address' => '東京都新宿区1-1-1',
            'building' => '旧住所マンション101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'item_name' => '腕時計',
        ]);

        $addressResponse = $this->actingAs($buyer)
            ->post('/purchase/address/' . $item->id, [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区2-2-2',
                'building' => 'テストビル202',
            ]);

        $addressResponse->assertRedirect('/purchase/' . $item->id);

        $addressResponse->assertSessionHas('purchase_address', [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区2-2-2',
            'building' => 'テストビル202',
        ]);

        $purchaseResponse = $this->actingAs($buyer)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'card',
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区2-2-2',
                'building' => 'テストビル202',
            ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区2-2-2',
            'building' => 'テストビル202',
        ]);
    }

    public function test_user_profile_information_is_displayed_correctly()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $otherUser = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'image' => 'profiles/test-user.jpg',
            'name' => 'プロフィールユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $sellingItem = Item::factory()->create([
            'user_id' => $user->id,
            'item_name' => '出品した腕時計',
        ]);

        $purchasedItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'item_name' => '購入したバッグ',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $sellResponse = $this->actingAs($user)
            ->get('/mypage?page=sell');

        $sellResponse->assertStatus(200);

        $sellResponse->assertSee(
            'storage/profiles/test-user.jpg',
            false
        );

        $sellResponse->assertSee('プロフィールユーザー');

        $sellResponse->assertSee('出品した腕時計');

        $sellResponse->assertDontSee('購入したバッグ');

        $buyResponse = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $buyResponse->assertStatus(200);

        $buyResponse->assertSee(
            'storage/profiles/test-user.jpg',
            false
        );

        $buyResponse->assertSee('プロフィールユーザー');

        $buyResponse->assertSee('購入したバッグ');

        $buyResponse->assertDontSee('出品した腕時計');
    }

    public function test_saved_profile_information_is_displayed_as_initial_values()
    {
        $user = User::factory()->create([
            'name' => '会員登録時の名前',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'image' => 'profiles/test-profile.jpg',
            'name' => 'プロフィールユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストマンション101',
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee(
            'storage/profiles/test-profile.jpg',
            false
        );

        $response->assertSee(
            'value="プロフィールユーザー"',
            false
        );

        $response->assertSee(
            'value="123-4567"',
            false
        );

        $response->assertSee(
            'value="東京都渋谷区1-2-3"',
            false
        );

        $response->assertSee(
            'value="テストマンション101"',
            false
        );
    }

    public function test_user_can_register_an_item_with_required_information()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $condition = Condition::factory()->create([
            'name' => '良好',
        ]);

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $image = UploadedFile::fake()->create(
            'watch.jpg',
            100,
            'image/jpeg'
        );

        $response = $this->actingAs($user)
            ->post('/sell', [
                'image' => $image,
                'categories' => [
                    $category1->id,
                    $category2->id,
                ],
                'condition_id' => $condition->id,
                'item_name' => '腕時計',
                'brand_name' => 'Rolex',
                'item_description' => '高級感のある腕時計です。',
                'price' => 15000,
            ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'item_name' => '腕時計',
            'brand_name' => 'Rolex',
            'item_description' => '高級感のある腕時計です。',
            'price' => 15000,
        ]);

        $item = Item::where('item_name', '腕時計')->firstOrFail();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);

        Storage::disk('public')->assertExists($item->image);
    }

    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    public function test_verification_link_points_to_mailhog()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(200);

        $response->assertSee('認証はこちらから');

        $response->assertSee(
            'href="http://localhost:8025"',
            false
        );

        $response->assertSee(
            'target="_blank"',
            false
        );
    }

    public function test_user_is_redirected_to_profile_page_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)
            ->get($verificationUrl);

        $response->assertRedirect('/mypage/profile');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

}