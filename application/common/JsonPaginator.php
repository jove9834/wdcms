<?php
/**
 * 接口分页返回JSON
 *
 * @author    jove9834
 * @copyright Copyright (c) 2018, Wedo, Inc. (http://www.wdeip.com/)
 * @link      http://wdeip.com
 * @since     Version 1.0
 */

namespace app\common;

use think\Paginator;

class JsonPaginator extends Paginator
{
    /**
     * render
     *
     * @return array
     */
    public function render()
    {
        $json = [
            'items' => $this->items(),
            'total' => $this->count(),
            'page' => $this->currentPage(),
            'size' => $this->listRows()
        ];

        return $json;
    }
}