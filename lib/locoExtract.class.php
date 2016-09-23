<?php
class locoExtract extends sfI18nExtract
{

  /**
   * Configures the current extract object.
   */
  public function configure()
  {
    $options = $this->i18n->getOptions();
  }


  public function extract()
  {
    // Extract from PHP files to find __() calls in actions/ lib/ and templates/ directories

    $messages = array();
    $messagesToUpdate = array();
    $b = new sfWebBrowser();
    $b->get(sfConfig::get('app_loco_api_root').'export/locale/'.$this->culture.'.xlf?index=id&key='.sfConfig::get('app_loco_api_read_key'));
    $xliff = $b->getResponseXml();
    $messageSource=$this->i18n->getMessageSource();
    foreach ($xliff->file->body->{'trans-unit'} as $transUnit){
      if((string)$transUnit->source){
        $messages[] = (string)$transUnit->source;
        if((string)$transUnit->target){
          // On a une traduction, on fera un update après.
          $messagesToUpdate[]=array(
              "source"  => (string)$transUnit->source,
              "target"  => (string)$transUnit->target
            );
        }
      }
    }
    $this->updateMessages($messages);
    foreach($messagesToUpdate as $asset){
      $messageSource->update($asset["source"], $asset["target"], '','messages');
    }
  }
}
