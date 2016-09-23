<?php


class locoApplicationExtract extends sfI18nExtract
{
  protected $extractObjects = array();

  /**
   * Configures the current extract object.
   */
  public function configure()
  {
    $this->extractObjects = array();

    $this->extractObjects[] = new locoExtract($this->i18n, $this->culture);
  }

  /**
   * Extracts i18n strings.
   *
   * This class must be implemented by subclasses.
   */
  public function extract()
  {
    foreach ($this->extractObjects as $extractObject)
    {
      $extractObject->extract();
    }
  }

  /**
   * Gets the current i18n strings.
   */
  public function getCurrentMessages()
  {
    return array_unique(array_merge($this->currentMessages, $this->aggregateMessages('getCurrentMessages')));
  }

  /**
   * Gets all i18n strings seen during the extraction process.
   */
  public function getAllSeenMessages()
  {
    return array_unique(array_merge($this->allSeenMessages, $this->aggregateMessages('getAllSeenMessages')));
  }

  protected function aggregateMessages($method)
  {
    $messages = array();
    foreach ($this->extractObjects as $extractObject)
    {
      $messages = array_merge($messages, $extractObject->$method());
    }

    return array_unique($messages);
  }
}
