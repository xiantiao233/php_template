<?php
require_once __DIR__ . '/../config.php';

class User
{
    private int $uid;
    private int $qq;
    private ?string $password;

    public function __construct(int $int)
    {
        $this->uid = $int;
        $this->qq = $int;
    }

    /**
     * 创建用户实例之后应该立即调用初始化
     * @param string $type qq|uid
     * @return int 0-用户不存在
     */
    public function initialization(string $type): int
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
            $stmt->bind_param('i', $this->uid);

            // 获取结果
            $stmt->execute();
            $result = $stmt->get_result();

            // 如果没有找到用户直接返回0
            if ($result->num_rows !== 1) return 0;

            // 获取返回值与关闭链接
            $returnValue = implode($result->fetch_assoc());
            $stmt->close();
            $mysqli->close();

            // QQ 与 uid变量赋值
            if ($type === 'qq') {
                $this->uid = $returnValue;
            } else {
                $this->qq = $returnValue;
            }

            return "$returnValue";
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }

    /**
     * 当用户不存在时可以向数据库中添加用户
     * @param string $password
     * @return int
     */
    public function createUser(string $password): int
    {
        try {
            $this->password = $password = hash('sha512', $password);
            $mysqli = (new MySQLer())->createMySQLConn();

            $stmt = $mysqli->prepare('INSERT INTO user_data (`qq`, `password`) VALUES (?, ?)');
            // 绑定参数
            $stmt->bind_param('is', $this->qq,$password);

            $stmt->execute();
            $stmt->close();
            $mysqli->close();

            return (new User($this->qq))->initialization('qq');
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }

    public function getQq(): int{return $this->qq;}
    public function getUid(): int{return $this->uid;}

    public function getPassword(): string
    {
        try {
            if ($this->password !== null) return $this->password;
            $mysqli = (new MySQLer())->createMySQLConn();

            $stmt = $mysqli->prepare('SELECT password FROM user_data WHERE uid = ?');

            // 绑定参数
            $stmt->bind_param('i', $this->uid);

            // 获取结果
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $mysqli->close();

            $this->password = implode($result->fetch_assoc());
            return $this->password;
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }
    public function setPassword($password): void
    {
        try {
            $password = hash('sha512', $password);
            $mysqli = (new MySQLer())->createMySQLConn();

            $stmt = $mysqli->prepare('UPDATE user_data SET password = ? WHERE uid = ?');
            // 绑定参数
            $stmt->bind_param('si', $password,$this->uid);

            $stmt->execute();
            $stmt->close();
            $mysqli->close();

            $this->password = $password;
        } catch (Exception $e) {
            xtSBack(10000021,$e);
        }
    }

    /**
     * 获取与判断登入session
     * @param string|null $token 有参数则为判断
     * @return string|bool 有参数返回bool
     */
    public function loginSession(?string $token = null): string|bool
    {
        $ip = (new Client())->getClientIP();

        if (!is_null($token)) {
            return $token === (new Rediser())->getValue("loginSession.$this->uid.$ip");
        }

        $token = (new GenerateRandom())->loginToken();
        (new Rediser())->setValue("loginSession.$this->uid.$ip", $token, getConfig('access_token.periodValidity'));
        return $token;
    }
}