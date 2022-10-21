<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;
use Phantom\Helper\Session;
use Phantom\Validator\Validator;

abstract class AbstractModel
{
    protected static $validator = null;
    protected static $hashMethod = null;
    protected static $config;
    protected $rules;
    protected $repository;
    public $fillable;
    public static function initConfiguration(Config $config)
    {
        self::$validator = new Validator();
        self::$config = $config;
        self::$hashMethod = $config->get("default.hash.method");
    }

    # Constructor sets object properties with $data
    # Constructor can create $rules and $repository if (rulesitory == true)
    # Constructor can get $rules and $repository from other model
    # rulesitory => Rules and Repository
    public function __construct(array $data = [], bool $rulesitory = true, ?string $model = null)
    {
        if ($rulesitory == true) {
            $namaspace = explode("\\", get_class($this));

            $namaspace[2] = $model ?? $namaspace[2];

            $rules = $namaspace[0] . "\Rules\\" . $namaspace[2] . "Rules";
            $repository = $namaspace[0] . "\Repository\\" . $namaspace[2] . "Repository";

            $this->rules = new $rules;
            $this->repository = new $repository;
        }

        $this->setArray($data);
    }

    # Method sets single property
    public function set(string $property, $value)
    {
        if ($this->propertyExists($property)) {
            $this->$property = $value;
        }
    }

    # Method sets a lot of properties
    public function setArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }
    }

    # Method return value of single property
    public function get(string $property)
    {
        if ($this->propertyExists($property)) {
            return $this->$property;
        }
    }

    # Method return values of a lot of properties
    public function getArray(array $array)
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {
            if (!in_array($key, $array)) {
                unset($properties[$key]);
            }
        }

        return $properties;
    }

    # Short method to validate $data
    protected function validate(array $data)
    {
        return self::$validator->validate($data, $this->rules);
    }

    # Short method to validate image
    protected function validateImage($FILE, $type)
    {
        return self::$validator->validateImage($FILE, $this->rules, $type);
    }

    # Method to find one record from database
    # array $conditions: ['id' => 5, 'name' => "bike"]
    # string $options: ORDER BY column_name ASC|DESC
    # bool $rulesitory: true|false => if created object need have access to $rules and $repository
    # $namespace: which of class will be created object
    public function find(array $conditions, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        if ($namaspace == null) {

            die();
            // $namaspace = get_class($this);
        }

        if ($data = $this->repository->get($conditions, $options)) {
            return new $namaspace($data, $rulesitory);
        }

        return null;
    }

    # Method to find one record from database by ID
    public function findById($id, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        return $this->find(['id' => $id], $options, $rulesitory, $namaspace);
    }

    # Method to find a lot of record from database
    public function findAll(array $conditions, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        $output = [];
        $data = $this->repository->getAll($conditions, $options);

        if ($namaspace == null) {
            // $namaspace = get_class($this);
        }

        if ($data) {
            foreach ($data as $item) {
                $output[] = new $namaspace($item, $rulesitory);
            }
        }

        return $output;
    }

    # Method adds record to database
    # if object was validated earlier we can skip validate in this method
    public function create(bool $validate = true)
    {
        if (($validate === true && $this->validate($this)) || $validate === false) {
            $this->repository->create($this);
            return true;
        }

        return false;
    }

    # Method updates current object | we can skip validate
    # array $toValidate: which properties will be validate
    public function update(array $toValidate = [], bool $validate = true)
    {
        $data = $this->getArray($toValidate);

        if (($validate === true && $this->validate($data)) || $validate === false) {
            $this->escape();
            $this->repository->update($this);
            Session::success('Dane zostały zaktualizowane'); // Default value
            return true;
        }
        return false;
    }

    # Method delete record from database
    # if property ID is sets this record will we deleted
    # else current object will be deleted
    public function delete(?int $id = null)
    {
        if ($id !== null) {
            $this->repository->delete((int) $id);
        } else {
            $this->repository->delete((int) $this->id);
        }
    }

    # Method do htmlentities on every property
    public function escape()
    {
        foreach ($this->fillable as $index => $key) {
            if (property_exists($this, $key)) {
                $this->$key = htmlentities((string) $this->$key);
            }
        }
    }

    # Method hash parameter
    public function hash($param, $method = null): string
    {
        return hash($method ?? self::$hashMethod, $param);
    }

    # Method hash name of file to create unique file name
    public function hashFile($file)
    {
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = $this->hash(date('Y-m-d H:i:s') . "_" . $file['name']);
        $file['name'] = $name . '.' . $type;
        return $file;
    }

    # Method upload file on server
    protected function uploadFile($path, $FILE): bool
    {
        $location = $path . basename($FILE["name"]);

        if (move_uploaded_file($FILE["tmp_name"], $location)) {
            return true;
        } else {
            Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            return false;
        }
    }

    # Method required to property exists
    private function propertyExists($name)
    {
        $properties = get_object_vars($this);

        if (array_key_exists($name, (array) $properties)) {
            return true;
        } else {
            throw new AppException("Property [" . $name . "] doesn't exists");
        }
    }

    # $url - Project location as "/.."
    public function getLocation()
    {
        # Project location => http://localhost/php-start/
        $location = self::$config->get('project.location');

        # Current URL => http://localhost/php-start/user/profile/update
        $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        # diff => user/profile/update
        $diff = str_replace($location, "", $url);

        $array = explode("/", $diff);
        $array = array_map(fn($element) => "/..", $array);
        array_pop($array); # We needs pop one element
        $output = implode("", $array);
        $output = ".$output/";

        return $output;
    }
}
