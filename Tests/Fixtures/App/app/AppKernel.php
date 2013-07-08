<?php                                                                                                                                                                                                              
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
 
class AppKernel extends Kernel
{
    public function registerBundles()
    {   
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Lmh\Bundle\RestApiBundle\LmhRestApiBundle(),
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
        return sys_get_temp_dir().'/LmhRestApiBundle/cache';
    }   
 
    /** 
     * @return string
     */
    public function getLogDir()
    {   
        return sys_get_temp_dir().'/LmhRestApiBundle/logs';
    }   
}
