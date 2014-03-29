<?php                                                                                                                                                                                                              
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
 
class AppKernel extends Kernel
{

    protected static $cacheDirPrefix;

    public function registerBundles()
    {   
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Matmar10\Bundle\RestApiBundle\Matmar10RestApiBundle(),
        );
    }   
 
    public function registerContainerConfiguration(LoaderInterface $loader)
    {   
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }   
 
    /** 
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__ . '/cache';
    }   
 
    /** 
     * @return string
     */
    public function getLogDir()
    {   
        return __DIR__ . '/logs';
    }
}
