<?php
include_once "Member.php";

class MemberManager
{
    protected $memberList;
    protected $filePath;

    public function __construct($filePath)
    {
        $this->memberList = [];
        $this->filePath = $filePath;
    }

    public function getMemberList()
    {
        return $this->memberList;
    }

    public function addMember($member)
    {
        $data = [
            "email" => $member->getEmail(),
            "phone" => $member->getPhone(),
            "password" => $member->getPassword()
        ];
        array_push($this->memberList, $data);
        $this->saveDataJson($this->memberList);
    }

    public function getDataJson()
    {
        $dataJson = file_get_contents($this->filePath);
        return json_decode($dataJson);
    }

    public function saveDataJson($data)
    {
        $dataJson = json_encode($data);
        file_put_contents($this->filePath, $dataJson);
    }

    public function getMemberListFromJson()
    {
        $data = $this->getDataJson();
        foreach ($data as $index => $obj) {
            array_push($this->memberList, $obj);
        }
        return $this->memberList;
    }

}