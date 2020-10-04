<?php

namespace App\Http\Controllers;

use App\Http\Helper\ValidatorHelper;
use App\Jobs\ProcessAutoCodeCreation;
use App\Models\DiscountCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class DiscountCodeController extends Controller
{
    public const RESULT_STATUS = 'resultStats';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    public const STATUS_CODE = 'statusCode';
    protected $body = null;
    protected $message;
    protected $statusCode = 400;

    protected $input ;
    public function __construct(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /**

         * @get('/api/admin/code')
         * @name('generated::SpQdL4Myny9fDaQt')
         * @middlewares(api, CheckToken)
         */
        //
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        /**

         * @post('/api/admin/code')
         * @name('generated::U0vD3nBYAC2Z7UAZ')
         * @middlewares(api, CheckToken)
         */

        // validate code data
        $validator = (new ValidatorHelper)->creationCodeValidator($request->post());

        if ($validator->fails()) {

            return response()->json([self::BODY => null, self::MESSAGE => $validator->errors()])->setStatusCode(400);

        }
        // validate Feature Array
        $isFeatureOk = (new ValidatorHelper)->validateFeatureArray($validator->validated()['features']) ;

        if (!$isFeatureOk) {

            return response()->json([self::BODY => null, self::MESSAGE => __('messages.checkDateIntervalAndPlan')])->setStatusCode(400);

        }
        $data = $validator->validated() ;

        // if code created_type is auto dispatch a job in queue
        if ($data['created_type'] === 'auto') {

            ProcessAutoCodeCreation::dispatch($data)->delay(1);
            return response()->json([self::BODY => null, self::MESSAGE => trans('messages.codeQueued', ['count' => $data['creation_code_count']])])->setStatusCode(200);
        }

        $result = (new DiscountCode)->createCode($data);

        return response()->json([self::BODY => $result[self::BODY], self::MESSAGE => $result[self::MESSAGE]])->setStatusCode($result[self::STATUS_CODE]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DiscountCode $discountCode
     * @return Response
     */
    public function update(Request $request, DiscountCode $discountCode)
    {
        /**

         * @patch('/api/admin/code/{id}')
         * @name('generated::VqzI6Ri8PiwGP0Qs')
         * @middlewares(api, CheckToken)
         */
        //
    }

//Illuminate\Database\Eloquent\ModelNotFoundException: No query results for model [App\Models\DiscountCode]. in /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:480
//Stack trace:
#0 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(102): Illuminate\Database\Eloquent\Builder->firstOrFail()
#1 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(57): App\Jobs\ProcessAutoCodeCreation->restoreModel(Object(Illuminate\Contracts\Database\ModelIdentifier))
#2 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(122): App\Jobs\ProcessAutoCodeCreation->getRestoredPropertyValue(Object(Illuminate\Contracts\Database\ModelIdentifier))
#3 [internal function]: App\Jobs\ProcessAutoCodeCreation->__unserialize(Array)
#4 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(54): unserialize('O:32:"App\\Jobs\\...')
#5 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\Queue\CallQueuedHandler->call(Object(Illuminate\Queue\Jobs\DatabaseJob), Array)
#6 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(406): Illuminate\Queue\Jobs\Job->fire()
#7 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(356): Illuminate\Queue\Worker->process('database', Object(Illuminate\Queue\Jobs\DatabaseJob), Object(Illuminate\Queue\WorkerOptions))
#8 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(158): Illuminate\Queue\Worker->runJob(Object(Illuminate\Queue\Jobs\DatabaseJob), 'database', Object(Illuminate\Queue\WorkerOptions))
#9 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(116): Illuminate\Queue\Worker->daemon('database', 'default', Object(Illuminate\Queue\WorkerOptions))
#10 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(100): Illuminate\Queue\Console\WorkCommand->runWorker('database', 'default')
#11 [internal function]: Illuminate\Queue\Console\WorkCommand->handle()
#12 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): call_user_func_array(Array, Array)
#13 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#14 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(95): Illuminate\Container\Util::unwrapIfClosure(Object(Closure))
#15 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(39): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#16 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Container/Container.php(596): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#17 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\Container\Container->call(Array)
#18 /home/django/workSpace/tohfeh/vendor/symfony/console/Command/Command.php(258): Illuminate\Console\Command->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#19 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#20 /home/django/workSpace/tohfeh/vendor/symfony/console/Application.php(920): Illuminate\Console\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#21 /home/django/workSpace/tohfeh/vendor/symfony/console/Application.php(266): Symfony\Component\Console\Application->doRunCommand(Object(Illuminate\Queue\Console\WorkCommand), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#22 /home/django/workSpace/tohfeh/vendor/symfony/console/Application.php(142): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#23 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Console/Application.php(93): Symfony\Component\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#24 /home/django/workSpace/tohfeh/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#25 /home/django/workSpace/tohfeh/artisan(37): Illuminate\Foundation\Console\Kernel->handle(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#26 {main}
}
