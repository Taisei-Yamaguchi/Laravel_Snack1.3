<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MemberIntegrateController extends Controller
{
    
    //regiter画面から メンバー統合画面にとぶ。
    public function memberIntegrate_index(Request $request){
        return view('member.integrate');
    }


    public function getMemberFromChat(Request $request)
    {
        //まず、snackアプリからmail,passをインプットしてもらい、
        //こっちでは、受け取ったimageデータをもとにダウンロードし、storageに保存する。
         // ユーザーが入力したメールアドレスとパスワードを取得
         $this->validate($request,Member::$rules_integrate);
         $form=$request->all();
         unset($form['_token']);

         $error=array();
         // ここでsnackアプリのDBにmailの重複チェックを実施し、エラーハンドリングを行う
        $existingMember = Member::where('mail', $form['mail'])->first();

        if ($existingMember) {
            // 既に登録されている場合のエラーハンドリング処理
            $error['mail']="このメールアドレスはすでにSnackアプリで使われています。";
            return view('member.integrate',
                ['error_others'=>$error],
            );
        }

        //本番環境で変更。
        $publicId = '';
        $secretKey = '';
        $url='http://localhost/member_integrate_API/member_integrate_API/public/api/get_member2';


          // Chat APIにリクエストを送信。
        $response = Http::withHeaders([
            'X-Public-Id'=>$publicId,
            'X-Secret-Key'=>$secretKey,
        ])->post($url, [
            'mail' => $form['mail'],
            'pass' => $form['pass']
        ]);

        // レスポンスのステータスコードをチェック
        if ($response->successful()) {
            // レスポンスが成功した場合、データを取得
            $responseData = $response->json();


            if(isset($responseData['name'])){

            // 必要なデータを取り出す
            $name = $responseData['name'];
            $mail = $responseData['mail'];
            $pass=$responseData['pass'];
            $image=$responseData['image'];
            
//データが渡されたリスポンスできてるか確認。
            // // 取得したデータを処理する。snack のDBに保存する。
            //  // snackアプリのDBに情報を保存する処理を実施
            $newMember = new Member();
            $newMember->name=$name;
            $newMember->mail = $mail;
            $newMember->pass = $pass;
            $newMember->image = $image;      
            $newMember->save();


            // //つづいて、Chat側にあるimageをダウンロードする。
            // 画像ダウンロード先のURL //本番環境で変更
                $imageUrl = 'http://localhost/laravel_chat2/Chat2/public/storage/member_images/'.$image;  
                // // HTTPリクエストを送信して画像を取得
                $imageData = @file_get_contents($imageUrl);
                
                //取得したimageDataに画像ファイルが存在するときにのみ保存処理を実行。
                if ($imageData !== false){
                    $imagePath = '../storage/app/public/member_images/'.$image; // 保存先のパス
                    file_put_contents($imagePath, $imageData);
                    // //storageにimageを保存。
                }

                // integrate成功画面に行く。name,mail,imageを一緒に。
                return view('member.integrate_success',
                    [
                        'name'=>$name,
                        'mail'=>$mail,
                        'image'=>$image,
                    ]
                );



            }//nameがあるか確認する。なければ統合画面にエラーメッセージを
            else{
                $errorAPI=$responseData['error'];
                return view('member.integrate',[
                    "errorAPI"=>$errorAPI,
                ]);
            }

        } 
        
        
        else {
            // // レスポンスがエラーの場合、エラーメッセージを取得
            $errorMessage = $response->json('error');
            // //メンバーが見つからなかったことを伝える。
            return response()->json([
                'error' => $errorMessage,
            ], $response->status());

            // return view('member.integrate',[
            //     "errorAPI"=>"APIサーバー側のエラー？",
            // ]);
        }


    }
}
