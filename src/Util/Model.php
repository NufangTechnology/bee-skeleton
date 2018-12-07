<?php
namespace Star\Util;

/**
 * Model
 *
 * @package Eye\Util
 */
class Model extends \Phalcon\Mvc\Model
{
    /**
     * 记录状态 - 运行
     */
    const ENABLE = 1;

    /**
     * 记录状态 - 禁用
     */
    const DISABLE = 0;

    /**
     * 获取错误信息
     *
     * @return array
     */
    public function getErrorMessages()
    {
        $messages = $this->getMessages();
        $data     = [];

        foreach ($messages as $message) {
            $data[] = [
                'filed'   => $message->getField(),
                'message' => $message->getMessage()
            ];
        }

        return $data;
    }

    /**
     * 根据记录ID检查记录是否存在
     *
     * @param $id
     * @return bool
     */
    static public function exist($id)
    {
        $record = self::findFirst($id);

        if (empty($record)) {
            return false;
        }

        return true;
    }

    /**
     * 启用指定记录
     *
     * @param $id
     * @return bool
     */
    static public function enable($id)
    {
        $record = self::findFirst($id);
        $record->enable = self::ENABLE;

        if ($record->update()) {
            return true;
        }

        return false;
    }

    /**
     * 禁用指定记录
     *
     * @param $id
     * @return bool
     */
    static public function disable($id)
    {
        $record = self::findFirst($id);
        $record->enable = self::DISABLE;

        return $record->update();
    }

    /**
     * 批量删除
     *
     * @param $whereCondition
     *
     * @return bool
     */
    static public function batchDelete($whereCondition)
    {
        $self = new static;

        // 执行删除
        return $self->getReadConnection()->delete($self->getSource(), $whereCondition);
    }
}
