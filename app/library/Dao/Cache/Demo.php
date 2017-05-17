<?php
namespace Dao\Cache;

/**
 * Class Demo
 *
 * @package Dao\Cache
 * @description 缓存服务示范
 *
 *              备注:
 *              1. 在Dao层中应该只关心数据的基本操作, 如: 保存、查询、删除等;
 *              2. 禁止跨层调用, Dao层仅包括数据访问的最基本操作, 因此禁止调用其他层, 包括上层以及其他Dao层的服务;
 *              3. 设计缓存操作时应考虑数据的命中率, 读操作频繁且命中率高的数据应同步到缓存, 减少持久化数据库压力;
 *              4. 缓存对象必须有过期时间, 避免不良占用内存;
 *              5. 缓存对象的键要有意义, 且不宜过长;
 *              6. 注意与其他Dao层服务配合使用的多源数据同步问题, 避免数据不一致问题和脏数据;
 *
 * //用户信息缓存
 * @method setData($key, array $data)
 * @method getData($key)
 * @method delData($key)
 */
class Demo extends \Base\Dao\Cache{

    const DATA_PREFIX = "DEMO_DATA";
    const TTL = 604800;

    /**
     * 构造器
     *
     * 设置缓存配置
     */
    public function __construct() {
        $this->setConfig("DATA", self::DATA_PREFIX, self::TTL);
    }

}