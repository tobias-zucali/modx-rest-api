<?php
/**
 * GET [URL]/resources returns a list of all resources.
 * GET [URL]/resources/1 returns the resource with id 1.
 * POST, PUT and DELETE is disabled.
 */
class myControllerResources extends modRestController {
    public $classKey = 'modResource';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';

    /**
     * based on https://github.com/modxcms/revolution/blob/2.x/core/model/modx/rest/modrestcontroller.class.php#L536-L555
     */
    public function read($id) {
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns',array(
                'field' => $this->primaryKeyField,
            )));
        }
        /** @var xPDOObject $object */
        $c = $this->getPrimaryKeyCriteria($id);
        $this->object = $this->modx->getObject($this->classKey,$c);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf',array(
                'class_key' => $this->classKey,
            )));
        }
        # TZ EDIT: use same functionality as $this->getList
        $objectArray = $this->prepareListObject($this->object);

        $afterRead = $this->afterRead($objectArray);
        if ($afterRead !== true && $afterRead !== null) {
            return $this->failure($afterRead === false ? $this->errorMessage : $afterRead);
        }
        return $this->success('',$objectArray);
    }

    protected function prepareListObject(xPDOObject $object) {
        $objectArray = $object->toArray();

        # TZ EDIT: add template variables
        $templateVarCollection = $object->getTemplateVarCollection($object);
        foreach ($templateVarCollection as $templateVar) {
            $objectArray[$templateVar->name] = $templateVar->value;
        }

        return $objectArray;
    }

    public function post()
    {
        return $this->failure('Not allowed');
    }

    public function put()
    {
        return $this->failure('Not allowed');
    }

    public function delete()
    {
        return $this->failure('Not allowed');
    }

}
