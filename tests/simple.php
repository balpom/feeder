<?php

namespace Balpom\Feeder\Creators;

ini_set('max_execution_time', '0');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('html_errors', 'off');
error_reporting(E_ALL);

include dirname(__DIR__) . '/vendor/autoload.php';

use Balpom\Entity\Structures\AbstractStructure;
use Balpom\Entity\Entities\AbstractEntity;
use Balpom\Entity\Collections\EntitySet;
use Balpom\Entity\Structures\Id;
use Balpom\Entity\Structures\Id\IntId;

class Person extends AbstractEntity
{
    protected static function fields(): void
    {
        self::$field['name'] = ['string' => true];
        self::$field['surname'] = ['string' => true];
        self::$field['jobId'] = ['string' => true];
    }

}

class Profession extends AbstractEntity
{
    protected static function fields(): void
    {
        self::$field['title'] = ['string' => true];
    }

}

class Workers extends AbstractStructure
{
    protected static function fields(): void
    {
        self::$field['professions'] = [EntitySet::class => true];
        self::$field['persons'] = [EntitySet::class => true];
    }

}

class SimpleCreator extends XmlCreator
{
    public function create(): string
    {
        $list = $this->xml->createElement('workers_list');

        $professions = $this->xml->createElement('professions');

        $items = $this->structure->getProfessions();
        foreach ($items as $item) {
            $job = $this->xml->createElement('job', $item->getTitle());
            $id = $item->getId();
            $job->setAttribute('id', $id);
            $job->setIdAttribute('id', true);
            $professions->appendChild($job);
        }

        $persons = $this->xml->createElement('persons');

        $items = $this->structure->getPersons();
        foreach ($items as $item) {
            $human = $this->xml->createElement('person');
            $id = $item->getId();
            $human->setAttribute('id', $id);
            $human->setIdAttribute('id', true);

            $this->appendElement($human, 'name', $item->getName());
            $this->appendElement($human, 'surname', $item->getSurname());
            $this->appendElement($human, 'job_id', $item->getJobId());

            $persons->appendChild($human);
        }

        $list->appendChild($professions);
        $list->appendChild($persons);

        $this->xml->appendChild($list);

        return $this->xml->saveXML();
    }

}

$professions = new EntitySet();
$professions->add(new Profession(id: new Id(id: 'wld'), title: 'welder'));
$professions->add(new Profession(id: new Id(id: 'prg'), title: 'programmer'));
$professions->add(new Profession(id: new Id(id: 'dir'), title: 'director'));
$professions->add(new Profession(id: new Id(id: 'acc'), title: 'accountant'));

$persons = new EntitySet();
$persons->add(new Person(id: new IntId(id: 111), name: 'Vasya', surname: 'Pupkin', jobId: 'dir'));
$persons->add(new Person(id: new IntId(id: 222), name: 'Kolya', surname: 'Morkovkin', jobId: 'wld'));
$persons->add(new Person(id: new IntId(id: 333), name: 'Petya', surname: 'Vasechkin', jobId: 'prg'));
$persons->add(new Person(id: new IntId(id: 444), name: 'John', surname: 'Doe', jobId: 'acc'));
$persons->add(new Person(id: new IntId(id: 555), name: 'Donald', surname: 'Smith', jobId: 'prg'));

$workers = new Workers(professions: $professions, persons: $persons);

$creator = new SimpleCreator($workers);
echo $creator->create();

/* Must be printed:

<?xml version="1.0" encoding="UTF-8"?>
<workers_list>
  <professions>
    <job id="wld">welder</job>
    <job id="prg">programmer</job>
    <job id="dir">director</job>
    <job id="acc">accountant</job>
  </professions>
  <persons>
    <person id="111">
      <name>Vasya</name>
      <surname>Pupkin</name>
      <job_id>dir</job_id>
    </person>
    <person id="222">
      <name>Kolya</name>
      <surname>Morkovkin</name>
      <job_id>wld</job_id>
    </person>
    <person id="333">
      <name>Petya</name>
      <surname>Vasechkin</name>
      <job_id>prg</job_id>
    </person>
    <person id="444">
      <name>John</name>
      <surname>Doe</name>
      <job_id>acc</job_id>
    </person>
    <person id="555">
      <name>Donald</name>
      <surname>Smith</name>
      <job_id>prg</job_id>
    </person>
  </persons>
</workers_list>
*/