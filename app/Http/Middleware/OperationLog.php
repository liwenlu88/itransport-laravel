<?php

namespace App\Http\Middleware;

use App\Models\OperationLog as ModelsOperationLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationLog
{
    /**
     * 操作日志中间件
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth()->user();

        // 处理请求
        $response = $next($request);

        // 获取当前请求的 URL 并去掉前缀 api/
        $path = substr($request->path(), 4);

        // 获取操作类型
        $method = $request->method();
        $operation = $this->getOperationType($method, $path);

        // 获取操作账户
        $account = $operation == 'Login' ? $request->input('account') : $admin->account;

        $operationLog = new ModelsOperationLog();

        $operationLog->account = $account;
        $operationLog->path = $path;
        $operationLog->method = $method;
        $operationLog->operation = $operation;
        $operationLog->ip_address = $request->ip();
        $operationLog->user_agent = $request->userAgent();
        $operationLog->request_data = json_encode($request->all());
        $operationLog->response_data = $response->getContent();
        $operationLog->status_code = $response->getStatusCode();

        // 如果是更新操作且响应状态码为 200，则记录原始数据和新数据
        if ($operation === 'Update' && $response->getStatusCode() === Response::HTTP_OK) {
            // 获取原始数据
            $originalData = json_decode($request->headers->get('X-Original-Data'), true);
            $operationLog->original_data = json_encode($originalData);
            $operationLog->new_data = json_encode($request->all());
        }

        $operationLog->save();

        // 返回原始响应
        return $response;
    }

    /**
     * 根据请求方法确定操作类型
     */
    protected function getOperationType($method, $path): string
    {
        if (strpos($path, 'login') && $method === 'POST') {
            return 'Login';
        }

        if (strpos($path, 'logout') && $method === 'POST') {
            return 'Logout';
        }

        return match ($method) {
            'GET' => 'Select',
            'POST' => 'Create',
            'PUT', 'PATCH' => 'Update',
            'DELETE' => 'Destroy',
            default => 'Unknown',
        };
    }
}
