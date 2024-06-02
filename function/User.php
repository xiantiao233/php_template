<?php
class User
{
    private int $uid;
    private int $qq;
    private string $password;
    public function __construct(int $int)
    {
        $this->uid = $int;
        $this->qq = $int;
    }

    public function initialization(string $type): bool
    {
        try {
            $mysqli = (new MySQLer())->createMySQLConn();

            // 准备 SQL 语句
            switch ($type) {
                case 'qq':
                    $stmt = $mysqli->prepare('SELECT uid FROM user_data WHERE qq = ?');
                    break;
                case 'uid':
                    $stmt = $mysqli->prepare('SELECT qq FROM user_data WHERE uid = ?');
                    break;
                default:
                    http_response_code(500);
                    exit("User type '$type' not found.");
            }

            // 绑定参数
            $stmt->bind_param('s', $this->uid);

            // 获取结果
            $result = $stmt->get_result();
            $stmt->close();
            $mysqli->close();

            return $result->num_rows === 1;
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }

    public function getPassword()
    {
        try {
            $mysqli = (new MySQLer())->createMySQLConn();

            $stmt = $mysqli->prepare('SELECT uid FROM user_data WHERE qq = ?');

            // 绑定参数
            $stmt->bind_param('s', $this->uid);

            // 获取结果
            $result = $stmt->get_result();
            $stmt->close();
            $mysqli->close();

            return $result->num_rows === 1;
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }
}